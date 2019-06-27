<?php
declare(strict_types=1);

namespace App\Payment;

use App\Events\OrderPaidEvent;
use App\Exceptions\NotifyDataErrorException;
use App\Exceptions\TradeNoUsedException;
use App\Models\Charge;
use App\Sn;
use App\Types\OrderPayStatus;
use App\Types\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Order
{
    /**
     * @param array $data
     * @return mixed
     * @throws TradeNoUsedException
     */
    public function create(array $data)
    {
        $data[Charge::CHARGE_NO] = Sn::generateOrderSn();

        // 检查订单是否存在
        $charge = Charge::where(Charge::APP_ID, data_get($data, Charge::APP_ID))
            ->where(Charge::ORDER_NO, data_get($data, Charge::ORDER_NO))
            ->first();

        if ($charge && $charge->id) {
            throw new TradeNoUsedException();
        } else {
            $data[Charge::PAID] = OrderPayStatus::WAIT_PAY;
            $data[Charge::STATUS] = OrderStatus::WAIT_PAY;

            $charge = Charge::create($data);
        }

        return $charge;
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

    public function paid(Charge $charge, array $params)
    {
        DB::transaction(function () use ($charge, $params) {
            $t = new Carbon();

            $transactionNo = 0;
            if ($charge->isAlipay()) {
                $transactionNo = data_get($params, 'trade_no');
            }
            if ($charge->isWechatPay()) {
                $transactionNo = data_get($params, 'transaction_id');
            }
            if ($charge->isQpay()) {
                $transactionNo = data_get($params, 'transaction_id');
            }

            if (!$transactionNo) {
                throw new NotifyDataErrorException();
            }

            $charge = Charge::where(Charge::ID, $charge->id)->lockForUpdate()->get();
            if ($charge->{Charge::PAID} != OrderPayStatus::PAID) {
                DB::tables('charges')->where(Charge::ID, $charge->{Charge::ID})
                    ->update([
                        $charge->{Charge::TRANSACTION_NO} => $transactionNo,
                        $charge->{Charge::TRANSACTION_ORG_DATA} => \GuzzleHttp\json_encode($params),
                        $charge->{Charge::PAID} => OrderPayStatus::PAID,
                        $charge->{Charge::PAY_AT} => $t,
                        $charge->{Charge::STATUS} => OrderStatus::PAID,
                        $charge->{Charge::UPDATED_AT} => $t,
                        $charge->{Charge::BUYER_ID} => data_get($params, 'buyer_id', ''),
                    ]);

                $charge->refresh();

                Log::channel('order')->info('支付成功, 订单状态更新成功', [
                    'charge' => $charge
                ]);
                event(new OrderPaidEvent($charge->{Charge::ID}));
            }
        });
    }
}
