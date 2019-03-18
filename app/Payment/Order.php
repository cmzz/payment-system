<?php

declare(strict_types=1);


namespace App\Payment;


use App\Exceptions\TradeNoUsedException as TradeNoUsedExceptionAlias;
use App\Models\Recharge;
use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;

class Order
{
    public function __construct()
    {

    }

    /**
     * @param array $data
     * @return mixed
     * @throws TradeNoUsedExceptionAlias
     * @throws \App\Exceptions\UndefinedChannelException
     */
    public function create(array $data)
    {
        // 检查订单是否存在
        $recharge = Recharge::where(Recharge::APP_ID, data_get($data, Recharge::APP_ID))
            ->where(Recharge::ORDER_NO, data_get($data, Recharge::ORDER_NO))
            ->first();

        if ($recharge && $recharge->id) {
//            throw new TradeNoUsedExceptionAlias();
        } else {
            $recharge = Recharge::create($data);
        }

        $data = [
            'subject'      => $recharge->{Recharge::SUBJECT},
            'body'      => $recharge->{Recharge::BODY},
            'out_trade_no' => $recharge->{Recharge::ID},
            'total_amount' => $recharge->getCentAmount(),
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
            'total_fee'         => 1,
            'spbill_create_ip'  => $recharge->{Recharge::CLIENT_IP},
            'fee_type'          => $recharge->{Recharge::CURRENCY},
            'notify_url' => route('notify_url'),
            'return_url' => route('return_url'),
        ];

        // 支付平台预下单
        $gateway = Gateway::get($recharge->{Recharge::CHANNEL});

        $response = $gateway->purchase($data)->send();
        dd($response);

        return $recharge;
    }

    public function get()
    {

    }

    public function canceled()
    {

    }

    public function closed()
    {

    }

    public function paid()
    {

    }
}
