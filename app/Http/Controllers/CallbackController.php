<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InvalidArgumentException;
use App\Models\App;
use App\Models\Recharge;
use App\Payment\TradeNo;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $orderNo = $request->get('out_trade_no');
        if (!$orderNo) {
            Log::error('同步毁掉参数中没有 out_trade_no');
            throw new InvalidArgumentException();
        }

        $orderInfo = TradeNo::decode($orderNo);

        if ($rechargeId = data_get($orderInfo, 2)) {
            try {
                $recharge = Recharge::where(Recharge::APP_ID, data_get($orderInfo, 0))
                    ->where(Recharge::ID, $rechargeId)
                    ->firstOrFail();

            } catch (ModelNotFoundException $e) {
                return view('order.404');
            }

            $app = $recharge->app;

            return redirect($app->{App::CALLBACK_URL}.'?'.build_query([
                    'recharge_id' => $recharge->{Recharge::ID},
                    'order_no' => $recharge->{Recharge::ORDER_NO}
                ]));
        }

        return view('order.404');
    }
}
