<?php
declare(strict_types=1);

namespace App\Payment\ResponseDataBuilder;

use App\Models\Recharge;

trait QpayTrait
{
    protected function savePreOrderId()
    {
        $data = $this->response->getData();

        if (strtolower(data_get($data, 'return_code')) == 'success' && strtolower(data_get($data,
                'result_code')) == 'success') {
            $this->recharge->{Recharge::NONCE_STR} = data_get($data, 'nonce_str');
            $this->recharge->{Recharge::PREPAY_ID} = data_get($data, 'prepay_id');
            $this->recharge->save();
        }
    }
}
