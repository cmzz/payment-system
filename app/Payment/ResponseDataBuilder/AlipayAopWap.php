<?php

declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class AlipayAopWap extends ResponseDataBuilder
{
    protected function process()
    {
        $this->data = ['redirect_url' => $this->response->getRedirectUrl()];
    }
}
