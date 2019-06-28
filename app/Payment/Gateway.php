<?php

declare(strict_types=1);

namespace App\Payment;

use App\Exceptions\PaymentRequestException;
use App\Exceptions\PreOrderFailedException;
use App\Exceptions\UndefinedChannelException;
use App\Models\Charge;
use App\Payment\ResponseDataBuilder\ResponseDataBuilderInterface;
use Illuminate\Http\Response;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;

class Gateway
{
    /** @var GatewayInterface */
    protected $gateway;

    /** @var Charge */
    protected $charge;

    /**
     * @return Gateway
     * @throws UndefinedChannelException
     */
    private function initGateway(): self
    {
        $channel = $this->charge->{Charge::CHANNEL};
        $conf = config('payment.gateways');

        if ($this->charge->isQpay()) {
            $gateway = Omnipay::create($channel);

            $gateway->setAppId(data_get($conf, 'qpay.app_id'));
            $gateway->setAppKey(data_get($conf, 'qpay.app_key'));
            $gateway->setMchId(data_get($conf, 'qpay.mech_id'));
            $gateway->setApiKey(data_get($conf, 'qpay.api_key'));

            $this->gateway = $gateway;
            return $this;
        } elseif ($this->charge->isAlipay()) {
            $gateway = Omnipay::create($channel);

            $gateway->setSignType(data_get($conf, 'alipay.sign_type')); //RSA/RSA2
            $gateway->setAppId(data_get($conf, 'alipay.app_id'));
            $gateway->setPrivateKey(data_get($conf, 'alipay.private_key'));
            $gateway->setAlipayPublicKey(data_get($conf, 'alipay.alipay_public_key'));
            $gateway->setNotifyUrl(route('notify_url.alipay'));
            $gateway->setReturnUrl(route('return_url'));

            $this->gateway = $gateway;
            return $this;
        } elseif ($this->charge->isWechatPay()) {
            $gateway = Omnipay::create($channel);

            $gateway->setAppId(data_get($conf, 'wx.app_id'));
            $gateway->setMchId(data_get($conf, 'wx.mech_id'));
            $gateway->setApiKey(data_get($conf, 'wx.api_key'));

            $this->gateway = $gateway;
            return $this;
        }

        throw new UndefinedChannelException();
    }

    /**
     * @param Charge $charge
     * @return Gateway
     * @throws UndefinedChannelException
     */
    public function setCharge(Charge $charge): self
    {
        $this->charge = $charge;
        $this->initGateway();

        return $this;
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
        if ($this->charge->isAlipay()) {
            $request = $this->gateway->purchase()->setBizContent(PreOrderData::build($this->charge));
        }

        if ($this->charge->isWechatPay() ||
            $this->charge->isQpay()) {
            $request = $this->gateway->purchase(PreOrderData::build($this->charge));
        }

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            \Log::channel('order')->error('预下单请求发送失败', [
                'charge' => $this->charge,
                'request_data' => $request->getData()
            ]);

            throw new PaymentRequestException();
        }

        if ($response->isSuccessful()) {
            \Log::channel('order')->info('预下单成功', [
                'charge' => $this->charge,
                'request_data' => $request->getData(),
                'response_data' => $response->getData()
            ]);

            $className = str_replace('_', '', $this->charge->{Charge::CHANNEL});
            $responseBuilder = "\\App\\Payment\\ResponseDataBuilder\\" . $className;

            /** @var ResponseDataBuilderInterface $build */
            $builder = new $responseBuilder($this->charge, $response);

            $prepayData = $builder->getData();
            \Log::channel('order')->info('预下单成功，返回数据到应用', [
                'charge' => $this->charge,
                'response_data' => $response->getData(),
                'data' => $prepayData
            ]);

            return $prepayData;
        } else {
            \Log::channel('order')->error('预下单失败', [
                'charge' => $this->charge,
                'request_data' => $request->getData(),
                'response_data' => $response->getData()
            ]);

            throw new PreOrderFailedException();
        }
    }

    /**
     * @param $params
     * @return Response
     * @throws \Exception
     */
    public function notify($params): Response
    {
        $request = $this->gateway->completePurchase([
            'request_params' => $params
        ]);

        try {
            $response = $request->send();

            if ($response->isPaid()) {
                if (!$this->charge->isAlipay()) {
                    $params = $response->getRequestData();
                    \Log::channel('order')->info('get params', $params);
                }

                (new Order)->paid($this->charge, $params);

                return response('success', 200)
                    ->header('Content-Type', 'text/plain');
            } else {
                return response('fail', 200)
                    ->header('Content-Type', 'text/plain');
            }
        } catch (\Exception $e) {
            \Log::channel('order')->error('notify error: '. $e->getMessage());
            throw $e;
        }
    }
}
