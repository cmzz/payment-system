<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Recharge;
use App\Payment\Gateway;
use App\Payment\TradeNo;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function index(Request $request)
    {
        $orderNo = $request->get('out_trade_no');
        $orderInfo = TradeNo::decode($orderNo);

        if ($rechargeId = data_get($orderInfo, 2)) {
            try {
                $recharge = Recharge::where(Recharge::APP_ID, data_get($orderInfo, 0))
                    ->where(Recharge::ID, $rechargeId)
                    ->firstOrFail();
            } catch (\Exception $e) {
                response('fail', 200)
                    ->header('Content-Type', 'text/plain');
            }

            return (new Gateway())->setRecharge($recharge)->notify($request->all());
        }
    }
}
