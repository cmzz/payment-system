<?php

declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class AlipayAopApp extends ResponseDataBuilder
{
    protected function process()
    {
        $this->data = ['order_string' => $this->response->getOrderString()];
    }
}
