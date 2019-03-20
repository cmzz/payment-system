<?php

declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class QPayJs extends ResponseDataBuilder
{
    use QpayTrait;

    protected function process()
    {
        $this->savePreOrderId();

        $this->data = ['order_data' => $this->response->getJsOrderData()];
    }
}
