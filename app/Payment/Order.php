<?php
declare(strict_types=1);

namespace App\Payment;

use App\Events\OrderPaidEvent;
use App\Exceptions\NotifyDataErrorException;
use App\Exceptions\TradeNoUsedException;
use App\Models\Recharge;
use App\Types\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Order
{
    /**
     * @param array $data
     * @return mixed
     * @throws TradeNoUsedException
     */
    public function create(array $data)
    {
        // 检查订单是否存在
        $recharge = Recharge::where(Recharge::APP_ID, data_get($data, Recharge::APP_ID))
            ->where(Recharge::ORDER_NO, data_get($data, Recharge::ORDER_NO))
            ->first();

        if ($recharge && $recharge->id) {
            throw new TradeNoUsedException();
        } else {
            $recharge = Recharge::create($data);
        }

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

    public function paid(Recharge $recharge, array $params)
    {
        DB::transaction(function () use ($recharge, $params) {
            $t = new Carbon();

            $transactionNo = 0;
            if ($recharge->isAlipay()) {
                $transactionNo = data_get($params, 'trade_no');
            }
            if ($recharge->isWechatPay()) {
                $transactionNo = data_get($params, 'transaction_id');
            }
            if ($recharge->isQpay()) {
                $transactionNo = data_get($params, 'transaction_id');
            }

            if (!$transactionNo) {
                throw new NotifyDataErrorException();
            }

            $recharge = Recharge::where(Recharge::ID, $recharge->id)->lockForUpdate()->get();
            if ($recharge->{Recharge::PAID} != 1) {
                DB::tables('recharges')->where(Recharge::ID, $recharge->{Recharge::ID})
                    ->update([
                        $recharge->{Recharge::TRANSACTION_NO} => $transactionNo,
                        $recharge->{Recharge::TRANSACTION_ORG_DATA} => \GuzzleHttp\json_encode($params),
                        $recharge->{Recharge::PAID} => 1,
                        $recharge->{Recharge::PAY_AT} => $t,
                        $recharge->{Recharge::STATUS} => OrderStatus::PAID,
                        $recharge->{Recharge::UPDATED_AT} => $t,
                        $recharge->{Recharge::BUYER_ID} => data_get($params, 'buyer_id', ''),
                    ]);

                event(new OrderPaidEvent($recharge->{Recharge::ID}));
            }
        });
    }
}
