<?php
declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

use App\Models\Charge;

trait WechatPayTrait
{
    protected function savePreOrderId()
    {
        $data = $this->response->getData();

        if (data_get($data, 'return_code') == 'success') {
            $this->charge->{Charge::NONCE_STR} = data_get($data, 'nonce_str');
            $this->charge->{Charge::PREPAY_ID} = data_get($data, 'prepay_id');
            $this->charge->save();
        }
    }
}
