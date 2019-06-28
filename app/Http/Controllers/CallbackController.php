<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InvalidArgumentException;
use App\Models\App;
use App\Models\Charge;
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
        $chargeNo = $request->get('out_trade_no');
        if (!$chargeNo) {
            Log::channel('order')->error('同步回调参数中没有 out_trade_no', [
                'params' => $request->all()
            ]);

            throw new InvalidArgumentException();
        }

        if ($chargeNo) {
            try {
                $charge = Charge::where(Charge::CHARGE_NO, $chargeNo)
                    ->firstOrFail();

            } catch (ModelNotFoundException $e) {
                return view('order.404');
            }

            $app = $charge->app;

            return redirect($app->{App::CALLBACK_URL}.'?'.build_query([
                    'charge_no' => $charge->{Charge::CHARGE_NO},
                    'order_no' => $charge->{Charge::ORDER_NO}
                ]));
        }

        return view('order.404');
    }
}
