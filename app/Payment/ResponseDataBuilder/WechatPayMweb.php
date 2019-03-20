<?php

declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class WechatPayMweb extends ResponseDataBuilder
{
    protected function process()
    {
        $this->data = [];
    }
}
