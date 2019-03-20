<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\Recharge;
use App\Payment\TradeNo;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function index(Request $request)
    {
        $orderNo = $request->get('out_trade_no');
        $orderInfo = TradeNo::decode($orderNo);

        if ($rechargeId = data_get($orderInfo, 2)) {
            $recharge = Recharge::where(Recharge::APP_ID, data_get($orderInfo, 0))
                ->where(Recharge::ID, $rechargeId)
                ->firstOrFail();

            $app = $recharge->app;

            return redirect($app->{App::CALLBACK_URL}.'?'.build_query([
                    'recharge_id' => $recharge->{Recharge::ID},
                    'order_no' => $recharge->{Recharge::ORDER_NO}
                ]));
        }
    }
}
