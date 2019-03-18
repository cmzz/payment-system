<?php

declare(strict_types=1);

namespace App\Payment;

use App\Exceptions\UndefinedChannelException;
use Illuminate\Support\Str;
use Omnipay\Omnipay;

class Gateway
{
    /**
     * @param $channel
     * @return \Omnipay\Common\GatewayInterface
     * @throws UndefinedChannelException
     */
    static public function get($channel): \Omnipay\Common\GatewayInterface
    {
        $cs = strtolower($channel);
        $conf = config('app.payment.gateways');

        if (Str::start($cs, 'qpay_')) {
            $gateway = Omnipay::create($channel);

            $gateway->setAppId(data_get($conf, 'qpay.app_id'));
            $gateway->setAppKey(data_get($conf, 'qpay.app_key'));
            $gateway->setMchId(data_get($conf, 'qpay.mech_id'));
            $gateway->setApiKey(data_get($conf, 'qpay.api_key'));

            return $gateway;
        } elseif (Str::start($cs, 'alipay_')) {
            $gateway = Omnipay::create($channel);

            $gateway->setSignType(data_get($conf, 'alipay.sign_type')); //RSA/RSA2
            $gateway->setAppId(data_get($conf, 'alipay.app_id'));
            $gateway->setPrivateKey(data_get($conf, 'alipay.private_key'));
            $gateway->setAlipayPublicKey(data_get($conf, 'alipay.alipay_public_key'));
            $gateway->setNotifyUrl(route('notify_url'));
            $gateway->setReturnUrl(route('return_url'));

            return $gateway;
        } elseif (Str::start($cs, 'wx_')) {
            $gateway = Omnipay::create($channel);

            $gateway->setAppId(data_get($conf, 'wx.app_id'));
            $gateway->setMchId(data_get($conf, 'wx.mech_id'));
            $gateway->setApiKey(data_get($conf, 'wx.api_key'));


            return $gateway;
        }

        throw new UndefinedChannelException();
    }
}
