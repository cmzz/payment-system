<?php
declare(strict_types=1);

namespace App\Types;

class OrderPayStatus extends BaseType
{
    // 待支付
    const WAIT_PAY = 0;
    // 已支付
    const PAID = 1;

    public static function names(): array
    {
        return [
            self::WAIT_PAY => 'wait_pay',
            self::PAID => 'paid',
        ];
    }


}
