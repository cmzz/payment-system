<?php
declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class WechatPayJs extends ResponseDataBuilder
{
    use WechatPayTrait;

    protected function process()
    {
        $this->savePreOrderId();

        $this->data = ['order_data' => $this->response->getJsOrderData()];
    }
}
