<?php

declare(strict_types=1);


namespace App\Types;


class OrderStatus extends BaseType
{
    const PAID = 1;
    const REFUND = 3;

    public static function names(): array
    {
        return [
            self::PAID => 'paid',
            self::REFUND => 'refund'
        ];
    }


}
