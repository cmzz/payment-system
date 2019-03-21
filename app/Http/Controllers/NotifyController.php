<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Recharge;
use App\Payment\Gateway;
use App\Payment\TradeNo;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifyController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();
        if (!$params) {
            $params = file_get_contents('php://input');
        }

        \Log::channel('order')->info('订单异步通知', [
            'params' => $params
        ]);

        $orderNo = data_get($params, 'out_trade_no');
        if (!$orderNo) {

        }

        $orderInfo = TradeNo::decode($orderNo);

        if ($rechargeId = data_get($orderInfo, 2)) {
            try {
                $recharge = Recharge::where(Recharge::APP_ID, data_get($orderInfo, 0))
                    ->where(Recharge::ID, $rechargeId)
                    ->firstOrFail();

                Log::channel('order')->info('订单recharge', [
                    'recharge' => $recharge
                ]);

                return (new Gateway())->setRecharge($recharge)->notify($request->all());
            } catch (\Exception $e) {
                response('fail', 200)
                    ->header('Content-Type', 'text/plain');
            }
        }
    }
}
