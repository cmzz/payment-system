<?php
declare(strict_types=1);

namespace App;


use Kra8\Snowflake\Snowflake;

class Sn
{
    public static function generateOrderSn(): string
    {
        return sprintf('charge_%d', static::getId());
    }

    public static function generateProductId(): string
    {
        return sprintf('prod_%d', static::getId());
    }

    public static function getId(): int
    {
        $sf = app(Snowflake::class);
        return $sf->next();
    }
}
