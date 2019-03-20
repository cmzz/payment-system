<?php

declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class WechatPayNative extends ResponseDataBuilder
{
    use WechatPayTrait;

    protected function process()
    {
        $this->savePreOrderId();

        $this->data = ['code_url' => $this->response->getCodeUrl()];
    }
}
