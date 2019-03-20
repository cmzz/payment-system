<?php

declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class QPayNative extends ResponseDataBuilder
{
    protected function process()
    {
        $this->data = [];
    }
}
