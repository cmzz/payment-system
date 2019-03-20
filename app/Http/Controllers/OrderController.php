<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\OrderCreatedEvent;
use App\Events\OrderPreOrderedEvent;
use App\Http\Requests\NewOrderRequest;
use App\Http\Requests\QueryOrderRequest;
use App\Models\Recharge;
use App\Payment\Gateway;
use App\Payment\Order;
use App\Response;

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
        $data = $request->getAll();
        $recharge = $this->order->create($data);

        try {
            $rspData = (new Gateway())
                ->setRecharge($recharge)
                ->preOrder();
        } catch (\Exception $e) {
            $recharge->delete();
            throw $e;
        }

        event(new OrderCreatedEvent($recharge));
        event(new OrderPreOrderedEvent($recharge->{Recharge::ID}, $rspData));

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
}
