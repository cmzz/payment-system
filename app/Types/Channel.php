<?php

declare(strict_types=1);

namespace App\Types;

use Illuminate\Support\Str;

class Channel
{
    const QPAY = 'QPay'; // QQ钱包支付通用网关
    const QPAY_APP = 'QPay_App'; // QQ 钱包 App 支付
    const QPAY_JS = 'QPay_Js'; // QQ 钱包公众号支付
    const QPAY_NATIVE = 'QPay_Native'; // QQ 钱包原生扫码支付
    const QPAY_MICROPAY = 'QPay_MicroPay'; // QQ 钱包付款码支付

    const WECHATPAY = '微信支付通用网关'; // 微信 App 支付
    const WECHATPAY_APP = 'WechatPay_App'; // 微信 App 支付
    const WECHATPAY_JS = 'WechatPay_Js'; // 微信网页、公众号、小程序支付网关
    const WECHATPAY_NATIVE = 'WechatPay_Native'; // 微信 Native 支付
    const WECHATPAY_POS = 'WechatPay_Pos'; // 微信刷卡支付网关
    const WECHATPAY_MWEB = 'WechatPay_Mweb'; // 微信H5支付

    const ALIPAY_AOPAPP = 'Alipay_AopApp'; // 支付宝 App 支付
    const ALIPAY_AOPWAP = 'Alipay_AopWap'; // 支付宝手机网站支付
    const ALIPAY_AOPPAGE = 'Alipay_AopPage'; // 支付宝电脑网站支付
    const ALIPAY_AOPF2F = 'Alipay_AopF2F'; // 支付宝扫码支付
    const ALIPAY_AOPJS = 'Alipay_AopJs'; // 支付宝小程序支付


    public static function names(): array
    {
        return [
            self::QPAY_APP,
            self::QPAY_JS,
            self::QPAY_NATIVE,
            self::QPAY_MICROPAY,

            self::WECHATPAY_APP,
            self::WECHATPAY_JS,
            self::WECHATPAY_NATIVE,
            self::WECHATPAY_POS,
            self::WECHATPAY_MWEB,

            self::ALIPAY_AOPAPP,
            self::ALIPAY_AOPWAP,
            self::ALIPAY_AOPPAGE,
            self::ALIPAY_AOPF2F,
            self::ALIPAY_AOPJS,
        ];
    }

    public static function isAlipay(string $channel): bool
    {
        return Str::startsWith(strtolower($channel), 'alipay');
    }

    public static function isWechatPay(string $channel): bool
    {
        return Str::startsWith(strtolower($channel), 'wechatpay');
    }

    public static function isQpay(string $channel): bool
    {
        return Str::startsWith(strtolower($channel), 'qpay');
    }
}
