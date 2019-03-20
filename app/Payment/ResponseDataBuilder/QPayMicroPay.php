<?php

declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class QPayMicroPay extends ResponseDataBuilder
{
    use QpayTrait;

    protected function process()
    {
        $this->savePreOrderId();

        $this->data = [];
    }
}
