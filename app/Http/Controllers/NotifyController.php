<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Payment\Gateway;
use App\Payment\TradeNo;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifyController extends Controller
{
    public function index(Request $request)
    {
        \Log::channel('order')->info('订单异步通知', [
            'params' => $request->all()
        ]);

        $chargeNo = $request->get('out_trade_no');

        if ($chargeNo) {
            try {
                $charge = Charge::where(Charge::CHARGE_NO, $chargeNo)
                    ->firstOrFail();

                Log::channel('order')->info('charge', [
                    'charge' => $charge
                ]);

                return (new Gateway())->setCharge($charge)->notify($request->all());
            } catch (\Exception $e) {
                Log::error($e->getTraceAsString());
                response('fail', 200)
                    ->header('Content-Type', 'text/plain');
            }
        }
    }
}
