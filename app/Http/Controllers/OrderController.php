<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\NewOrderRequest;
use App\Http\Requests\QueryOrderRequest;
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
     */
    public function store(NewOrderRequest $request)
    {
        $data = $request->getAll();

        $recharge = $this->order->create($data);

        $rspData = (new Gateway())
            ->setRecharge($recharge)
            ->preOrder();

        return Response::successData($rspData);
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
