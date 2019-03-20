<?php

declare(strict_types=1);

namespace App\Payment;

use App\Exceptions\PaymentRequestException;
use App\Exceptions\PreOrderFailedException;
use App\Exceptions\UndefinedChannelException;
use App\Models\Recharge;
use App\Payment\ResponseDataBuilder\ResponseDataBuilderInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
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

    /**
     * @return Gateway
     * @throws UndefinedChannelException
     */
    private function initGateway(): self
    {
        $channel = $this->recharge->{Recharge::CHANNEL};
        $conf = config('payment.gateways');

        if ($this->recharge->isQpay()) {
            $gateway = Omnipay::create($channel);

            $gateway->setAppId(data_get($conf, 'qpay.app_id'));
            $gateway->setAppKey(data_get($conf, 'qpay.app_key'));
            $gateway->setMchId(data_get($conf, 'qpay.mech_id'));
            $gateway->setApiKey(data_get($conf, 'qpay.api_key'));

            $this->gateway = $gateway;
            return $this;
        } elseif ($this->recharge->isAlipay()) {
            $gateway = Omnipay::create($channel);

            $gateway->setSignType(data_get($conf, 'alipay.sign_type')); //RSA/RSA2
            $gateway->setAppId(data_get($conf, 'alipay.app_id'));
            $gateway->setPrivateKey(data_get($conf, 'alipay.private_key'));
            $gateway->setAlipayPublicKey(data_get($conf, 'alipay.alipay_public_key'));
            $gateway->setNotifyUrl(route('notify_url'));
            $gateway->setReturnUrl(route('return_url'));

            $this->gateway = $gateway;
            return $this;
        } elseif ($this->recharge->isWechatPay()) {
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
     * @param Recharge $recharge
     * @return Gateway
     * @throws UndefinedChannelException
     */
    public function setRecharge(Recharge $recharge): self
    {
        $this->recharge = $recharge;
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
        if ($this->recharge->isAlipay()) {
            $request = $this->gateway->purchase()->setBizContent(PreOrderData::build($this->recharge));
        }

        if ($this->recharge->isWechatPay()) {
            $request = $this->gateway->purchase(PreOrderData::build($this->recharge));
        }

        if ($this->recharge->isQpay()) {
            $request = $this->gateway->purchase(PreOrderData::build($this->recharge));
        }

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            throw new PaymentRequestException();
        }

        dump($response->getData());

        if ($response->isSuccessful()) {
            $className = str_replace('_', '', $this->recharge->{Recharge::CHANNEL});
            $responseBuilder = "\\App\\Payment\\ResponseDataBuilder\\" . $className;

            /** @var ResponseDataBuilderInterface $build */
            $builder = new $responseBuilder($this->recharge, $response);

            return $builder->getData();
        } else {
            dump($request->getData());

            throw new PreOrderFailedException();
        }
    }

    public function notify(array $params): Response
    {
        $request = $this->gateway->completePurchase();
        $request->setParams($params);

        try {
            $response = $request->send();

            if($response->isPaid()){
                (new Order)->paid($this->recharge, $params);

                return response('success', 200)
                    ->header('Content-Type', 'text/plain');
            }else{
                return response('fail', 200)
                    ->header('Content-Type', 'text/plain');
            }
        } catch (\Exception $e) {
            return response('fail', 200)
                ->header('Content-Type', 'text/plain');
        }
    }
}
