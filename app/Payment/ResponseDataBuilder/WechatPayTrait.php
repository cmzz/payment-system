<?php

declare(strict_types=1);


namespace App\Payment\ResponseDataBuilder;


use App\Models\Recharge;

trait WechatPayTrait
{
    protected function savePreOrderId()
    {
        $data = $this->response->getData();

        if (data_get($data, 'return_code') == 'success') {
            $this->recharge->{Recharge::NONCE_STR} = data_get($data, 'nonce_str');
            $this->recharge->{Recharge::PREPAY_ID} = data_get($data, 'prepay_id');
            $this->recharge->save();
        }
    }
}
