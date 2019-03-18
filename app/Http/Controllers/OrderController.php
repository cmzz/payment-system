<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewOrderRequest;

class OrderController extends Controller
{
    public function store(NewOrderRequest $request)
    {
        $orderNo = $request->get($request::ORDER_NO, 0);
        
    }
}
