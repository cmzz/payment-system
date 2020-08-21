<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\OrderCreatedEvent;
use App\Events\OrderPreOrderedEvent;
use App\Http\Requests\NewOrderRequest;
use App\Http\Requests\QueryOrderRequest;
use App\Models\Charge;
use App\Payment\Gateway;
use App\Payment\Order;
use App\Response;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * 下单
     * @param NewOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UndefinedChannelException
     * @throws \App\Exceptions\PreOrderFailedException
     * @throws \App\Exceptions\TradeNoUsedException
     * @throws \Exception
     */
    public function store(NewOrderRequest $request)
    {
        \Log::info('receive request', [
            'random' => mt_rand(10000, 999999),
            'time' => time(),
            'params' => $request->allParams(),
            'headers' => $request->headers
        ]);

        $data = $request->allParams();
        \Log::channel('order')->info('创建订单', [
            'params' => $data,
            'headers' => $request->headers
        ]);

        $charge = $this->order->create($data);

        try {
            $rspData = (new Gateway())
                ->setCharge($charge)
                ->preOrder();
        } catch (\Exception $e) {
            $charge->delete();
            throw $e;
        }

        event(new OrderCreatedEvent($charge));
        event(new OrderPreOrderedEvent($charge->{Charge::ID}, $rspData));

        return Response::successData($rspData);
    }

    /**
     * 查询订单
     * @param QueryOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(QueryOrderRequest $request)
    {

        return Response::success();
    }

    public function refund(Request $request, $orderId)
    {

    }
}
