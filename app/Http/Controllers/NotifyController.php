<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Recharge;
use App\Payment\Gateway;
use App\Payment\TradeNo;
use App\Types\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class NotifyController extends Controller
{
    public function alipay(Request $request)
    {
        $params = $request->all();
        \Log::channel('order')->info('支付宝异步通知', [
            'params' => $params
        ]);

        $orderNo = $request->get('out_trade_no');
        $orderInfo = TradeNo::decode($orderNo);

        if ($rechargeId = data_get($orderInfo, 2)) {
            try {
                $recharge = Recharge::where(Recharge::APP_ID, data_get($orderInfo, 0))
                    ->where(Recharge::ID, $rechargeId)
                    ->firstOrFail();

                Log::channel('order')->info('订单recharge', [
                    'recharge' => $recharge
                ]);

                $gateway = (new Gateway())->setRecharge($recharge)->setParams($params);
                $aliPayGateway = $gateway->getGateway();

                $req = $aliPayGateway->completePurchase();
                $req->setParams($params);
                $response = $req->send();

                return $gateway->notify($response);
            } catch (\Exception $e) {
                response('fail', Response::HTTP_INTERNAL_SERVER_ERROR)
                    ->header('Content-Type', 'text/plain');
            }
        }
    }

    public function wechatPay()
    {
        return $this->tenpay(Channel::WECHATPAY);
    }

    public function qpay()
    {
        return $this->tenpay(Channel::QPAY);
    }

    private function tenpay($channel): \Illuminate\Http\Response
    {
        $params = file_get_contents('php://input');
        \Log::channel('order')->info('财付通异步通知原始请求参数', [
            'params' => $params
        ]);

        $gateway = (new Gateway())->initGateway($channel);
        $g = $gateway->getGateway();
        $req = $g->completePurchase([
            'request_params' => $params
        ]);
        $response = $req->send();

        Log::channel('order')->info('tenpay异步通知解析后的数据', [
            'data' => $response->getRequestData()
        ]);

        try {
            return $gateway->setParams($response->getRequestData())->notify($response);
        } catch (\Exception $e) {
            Log::channel('order')->error('异步通知后续处理失败', [
                'message' => $e->getMessage()
            ]);

            return response('fail', Response::HTTP_INTERNAL_SERVER_ERROR)
                ->header('Content-Type', 'text/plain');
        }
    }
}
