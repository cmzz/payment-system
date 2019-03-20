<?php

declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class AlipayAopJs extends ResponseDataBuilder
{
    protected function process()
    {
        $this->data = [];
    }
}
