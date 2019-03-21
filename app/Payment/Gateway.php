<?php

declare(strict_types=1);

namespace App\Payment;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\PaymentRequestException;
use App\Exceptions\PreOrderFailedException;
use App\Exceptions\UndefinedChannelException;
use App\Models\App;
use App\Models\Recharge;
use App\Payment\ResponseDataBuilder\ResponseDataBuilderInterface;
use App\Types\Channel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;

class Gateway
{
    /** @var GatewayInterface */
    protected $gateway;

    /** @var Recharge */
    protected $recharge;

    protected $params;

    /**
     * @param string $channel
     * @return Gateway
     */
    public function initGateway(string $channel): self
    {
        $conf = config('payment.gateways');
        $gateway = Omnipay::create($channel);

        if (Channel::isQpay($channel)) {
            $gateway->setAppId(data_get($conf, 'qpay.app_id'));
            $gateway->setAppKey(data_get($conf, 'qpay.app_key'));
            $gateway->setMchId(data_get($conf, 'qpay.mech_id'));
            $gateway->setApiKey(data_get($conf, 'qpay.api_key'));

            $this->gateway = $gateway;
            return $this;
        } elseif (Channel::isAlipay($channel)) {
            $gateway->setSignType(data_get($conf, 'alipay.sign_type')); //RSA/RSA2
            $gateway->setAppId(data_get($conf, 'alipay.app_id'));
            $gateway->setPrivateKey(data_get($conf, 'alipay.private_key'));
            $gateway->setAlipayPublicKey(data_get($conf, 'alipay.alipay_public_key'));
            $gateway->setNotifyUrl(route('notify_url.alipay'));
            $gateway->setReturnUrl(route('return_url'));

            $this->gateway = $gateway;
            return $this;
        } elseif (Channel::isWechatPay($channel)) {
            $gateway->setAppId(data_get($conf, 'wx.app_id'));
            $gateway->setMchId(data_get($conf, 'wx.mech_id'));
            $gateway->setApiKey(data_get($conf, 'wx.api_key'));

            $this->gateway = $gateway;
            return $this;
        }

        throw new UndefinedChannelException();
    }

    /**
     * @param Recharge $recharge
     * @return Gateway
     * @throws UndefinedChannelException
     */
    public function setRecharge(Recharge $recharge): self
    {
        $this->recharge = $recharge;
        $this->initGateway($this->recharge->{Recharge::CHANNEL});

        return $this;
    }

    public function setParams($params)
    {
        $this->params = $params;

        if (!$this->recharge) {
            $orderNo = data_get($params, 'out_trade_no');
            $orderInfo = TradeNo::decode($orderNo);

            if ($rechargeId = data_get($orderInfo, 2)) {
                try {
                    $recharge = Recharge::where(Recharge::APP_ID, data_get($orderInfo, 0))
                        ->where(Recharge::ID, $rechargeId)
                        ->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    \Log::channel('order')->error('异步通知处理失败，未找到对应订单', [
                        'params' => $params,
                        'order_no' => $orderInfo,
                        'recharge_id' => $rechargeId
                    ]);

                    throw $e;
                }

                \Log::channel('order')->info('订单recharge', [
                    'recharge' => $recharge
                ]);

                $this->recharge = $recharge;
            } else {
                \Log::channel('order')->error('解析订单号出错', [
                    'params' => $this->params
                ]);

                throw new InvalidArgumentException('错误的外部订单号');
            }
        }

        return $this;
    }

    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * 第三方平台下单
     * @return array
     * @throws PreOrderFailedException
     */
    public function preOrder(): array
    {
        /** @var ResponseInterface $response */
        /** @var RequestInterface $request */
        if (Channel::isAlipay($this->recharge->{Recharge::CHANNEL})) {
            $request = $this->gateway->purchase()->setBizContent(PreOrderData::build($this->recharge));
        }

        if (Channel::isWechatPay($this->recharge->{Recharge::CHANNEL}) ||
            Channel::isQpay($this->recharge->{Recharge::CHANNEL})) {
            $request = $this->gateway->purchase(PreOrderData::build($this->recharge));
        }

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            \Log::channel('order')->error('预下单请求发送失败', [
                'recharge' => $this->recharge->toArray(),
                'request_data' => $request->getData()
            ]);

            throw new PaymentRequestException();
        }

        if ($response->isSuccessful()) {
            \Log::channel('order')->info('预下单成功', [
                'recharge' => $this->recharge->toArray(),
                'request_data' => $request->getData(),
                'response_data' => $response->getData()
            ]);

            $className = str_replace('_', '', $this->recharge->{Recharge::CHANNEL});
            $responseBuilder = "\\App\\Payment\\ResponseDataBuilder\\" . $className;

            /** @var ResponseDataBuilderInterface $build */
            $builder = new $responseBuilder($this->recharge, $response);

            $prepayData = $builder->getData();
            \Log::channel('order')->info('预下单成功，返回数据到应用', [
                'recharge' => $this->recharge->toArray(),
                'response_data' => $response->getData(),
                'data' => $prepayData
            ]);

            return $prepayData;
        } else {
            \Log::channel('order')->error('预下单失败', [
                'recharge' => $this->recharge,
                'request_data' => $request->getData(),
                'response_data' => $response->getData()
            ]);

            throw new PreOrderFailedException();
        }
    }

    public function notify(ResponseInterface $response): Response
    {
        try {
            if ($response->isPaid()) {
                \Log::channel('order')->info('异步通知结果为支付成功', [
                    'request_data' => $response->getRequestData()
                ]);

                (new Order)->paid($this->recharge, $this->params);

                // todo 待优化 发送异步通知到应用服务器
                $this->recharge->refresh();

                $app = $this->recharge->app;
                if ($notifyUrl = $app->{App::NOTIFY_URL}) {
                    // todo 推送的数据需要加密
                    $notifyResponse = \Requests::post($notifyUrl, [], [
                        'event' => 'order.paid',
                        'server_time' => new Carbon(),
                        'data' => [
                            'recharge' => $this->recharge
                        ]
                    ]);

                    if ($notifyResponse->status_code == 200) {
                        \Log::channel('order')->error('支付结果通知应用服务器成功', [
                            'status_code' => $notifyResponse->status_code,
                            'body' => $notifyResponse->body
                        ]);

                        return response('success', \Symfony\Component\HttpFoundation\Response::HTTP_OK)
                            ->header('Content-Type', 'text/plain');
                    } else {
                        \Log::channel('order')->error('支付结果通知应用服务器失败', [
                            'status_code' => $notifyResponse->status_code,
                            'body' => $notifyResponse->body
                        ]);
                    }
                }
            }

            \Log::channel('order')->error('异步通知结果为支付失败', [
                'request_data' => $response->getRequestData()
            ]);
        } catch (\Exception $e) {
            // nothing
            \Log::channel('order')->error('服务器收到异步通知，但处理失败', [
                'message' => $e->getMessage(),
                'params' => $this->params,
                'recharge' => $this->recharge->toArray()
            ]);
        }

        return response('fail', \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR)
            ->header('Content-Type', 'text/plain');
    }
}
