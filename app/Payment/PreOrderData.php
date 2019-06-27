<?php
declare(strict_types=1);

namespace App\Payment;

use App\Models\Charge;
use App\Types\Channel;

class PreOrderData
{
    static public function build(Charge $charge)
    {
        if ($charge->isAlipay()) {
            $data = [
                'subject' => $charge->{Charge::SUBJECT},
                'body' => $charge->{Charge::BODY},
                'out_trade_no' => $charge->{Charge::CHARGE_NO},
                'total_amount' => $charge->getYuanAmount(),
                'product_code' => static::getAlipayProductCode($charge->{Charge::CHANNEL}),
            ];

            return $data;
        }

        if ($charge->isWechatPay()) {
            return $data = [
                'body' => $charge->{Charge::BODY},
                'out_trade_no' => TradeNo::encode($charge->{Charge::APP_ID}, $charge->{Charge::ORDER_NO},
                    $charge->{Charge::ID}),
                'total_fee' => $charge->{Charge::AMOUNT},
                'spbill_create_ip' => $charge->{Charge::CLIENT_IP},
                'fee_type' => strtoupper($charge->{Charge::CURRENCY}),
                'notify_url' => route('notify_url')
            ];
        }

        if ($charge->isQpay()) {
            return $data = [
                'body' => $charge->{Charge::SUBJECT},
                'out_trade_no' => TradeNo::encode($charge->{Charge::APP_ID}, $charge->{Charge::ORDER_NO},
                    $charge->{Charge::ID}),
                'total_fee' => $charge->{Charge::AMOUNT},
                'spbill_create_ip' => $charge->{Charge::CLIENT_IP},
                'fee_type' => strtoupper($charge->{Charge::CURRENCY}),
                'notify_url' => route('notify_url')
            ];
        }
    }

    static function getAlipayProductCode(string $channel): string
    {
        switch ($channel) {
            case Channel::ALIPAY_AOPPAGE:
                return 'FAST_INSTANT_TRADE_PAY';
            case Channel::ALIPAY_AOPWAP:
                return 'QUICK_WAP_PAY';
            case Channel::ALIPAY_AOPAPP:
                return 'QUICK_MSECURITY_PAY';
            case Channel::ALIPAY_AOPJS:
                return '';
            case Channel::ALIPAY_AOPF2F:
                return '';
        }
    }
}
