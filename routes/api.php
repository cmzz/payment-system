<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    // 同步回调
    Route::any('/callback', 'CallbackController@index');

    // 异步通知
    Route::any('/notify', 'NofifyController@index');

    // 下单
    Route::post('/order', 'OrderController@store');

    // 支付订单
    Route::post('/order/{id}/pay', 'PayController@store')->where('id', '[0-9]+');
});
