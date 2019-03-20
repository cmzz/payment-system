<?php
declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

class AlipayAopF2F extends ResponseDataBuilder
{
    protected function process()
    {
        $this->data = [];
    }
}
