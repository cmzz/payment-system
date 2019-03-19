<?php

declare(strict_types=1);


namespace App\Payment;


class TradeNo
{
    public static function encode(int $appId, string $tradeNo, int $id): string
    {
        return sprintf('%d-%s-%d', $appId, $tradeNo, $id);
    }

    public static function decode($orderNo): array
    {
        return explode('-', $orderNo);
    }
}
