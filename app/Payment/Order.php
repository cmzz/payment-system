<?php
declare(strict_types=1);

namespace App\Payment;

use App\Events\OrderPaidEvent;
use App\Exceptions\NotifyDataErrorException;
use App\Exceptions\TradeNoUsedException;
use App\Models\App;
use App\Models\Recharge;
use App\Types\Channel;
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
        // 检查订单是否存在
        $recharge = Recharge::where(Recharge::APP_ID, data_get($data, Recharge::APP_ID))
            ->where(Recharge::ORDER_NO, data_get($data, Recharge::ORDER_NO))
            ->first();

        if ($recharge && $recharge->id) {
            throw new TradeNoUsedException();
        } else {
            $data[Recharge::PAID] = OrderPayStatus::WAIT_PAY;
            $data[Recharge::STATUS] = OrderStatus::WAIT_PAY;

            $app = current_app();
            $data[Recharge::USER_ID] = $app->{App::USER_ID};

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
            if (Channel::isAlipay($recharge->{Recharge::CHANNEL})) {
                $transactionNo = data_get($params, 'trade_no');
            }
            if (Channel::isWechatPay($recharge->{Recharge::CHANNEL})) {
                $transactionNo = data_get($params, 'transaction_id');
            }
            if (Channel::isQpay($recharge->{Recharge::CHANNEL})) {
                $transactionNo = data_get($params, 'transaction_id');
            }

            if (!$transactionNo) {
                throw new NotifyDataErrorException();
            }

            $recharge = Recharge::where(Recharge::ID, $recharge->{Recharge::ID})
                ->lockForUpdate()
                ->first();

            if ($recharge->{Recharge::PAID} != OrderPayStatus::PAID) {
                DB::table('recharges')->where(Recharge::ID, $recharge->{Recharge::ID})
                    ->update([
                        Recharge::TRANSACTION_NO => $transactionNo,
                        Recharge::TRANSACTION_ORG_DATA => \GuzzleHttp\json_encode($params),
                        Recharge::PAID => OrderPayStatus::PAID,
                        Recharge::PAY_AT => $t->toDateTimeString(),
                        Recharge::STATUS => OrderStatus::PAID,
                        Recharge::BUYER_ID => data_get($params, 'buyer_id', ''),
                        Recharge::UPDATED_AT => $t->toDateTimeString(),
                    ]);

                $recharge->refresh();

                Log::channel('order')->info('支付成功, 订单状态更新成功', [
                    'recharge' => $recharge
                ]);

                event(new OrderPaidEvent($recharge->{Recharge::ID}));
            }
        });
    }
}
