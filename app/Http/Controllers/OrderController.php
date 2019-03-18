<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\NewOrderRequest;
use App\Http\Requests\QueryOrderRequest;
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
     * @throws \App\Exceptions\TradeNoUsedException
     * @throws \App\Exceptions\UndefinedChannelException
     */
    public function store(NewOrderRequest $request)
    {
        $data = $request->getAll();

        $this->order->create($data);

        return Response::successData($request->all());
    }

    /**
     * 查询订单
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(QueryOrderRequest $request)
    {

        return Response::success();
    }
}
