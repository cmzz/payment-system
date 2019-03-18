<?php

declare(strict_types=1);


namespace App\Types;


class Channel
{
    const QPAY = 'QPay_App'; // QQ 钱包 App 支付
    const QPAY_PUB = 'QPay_Js'; // QQ 钱包公众号支付
    const QPAY_NATIVE = 'QPay_Native'; // QQ 钱包原生扫码支付
    const QPAY_MICROPAY = 'QPay_MicroPay'; // QQ 钱包付款码支付

    const wx = 'WechatPay_App'; // 微信 App 支付
    const WX_PUB = 'WechatPay_Js'; // 微信网页、公众号、小程序支付网关
    const wx_Native = 'WechatPay_Native'; // 微信 Native 支付
    const WX_POS = 'WechatPay_Pos'; // 微信刷卡支付网关
    const WX_WAP = 'WechatPay_Mweb'; // 微信H5支付

    const ALIPAY = 'Alipay_AopApp'; // 支付宝 App 支付
    const ALIPAY_WAP = 'Alipay_AopWap'; // 支付宝手机网站支付
    const ALIPAY_PC = 'Alipay_AopPage'; // 支付宝电脑网站支付
    const ALIPAY_F2F = 'Alipay_AopF2F'; // 支付宝扫码支付
    const ALIPAY_JS = 'Alipay_AopJs'; // 支付宝小程序支付


    public static function names(): array
    {
        return [
            self::QPAY,
            self::QPAY_PUB,
            self::QPAY_NATIVE,
            self::QPAY_MICROPAY,

            self::wx,
            self::WX_PUB,
            self::wx_Native,
            self::WX_POS,
            self::WX_WAP,

            self::ALIPAY,
            self::ALIPAY_WAP,
            self::ALIPAY_PC,
            self::ALIPAY_F2F,
            self::ALIPAY_JS,
        ];
    }
}
