<?php

use Illuminate\Support\Facades\Route;

// 同步回调
Route::any('/order/callback', 'CallbackController@index')->name('return_url');

// 异步通知
Route::any('/order/notify/alipay', 'NotifyController@alipay')->name('notify_url.alipay');
Route::any('/order/notify/qpay', 'NotifyController@qpay')->name('notify_url.qpay');
Route::any('/order/notify/wechatpay', 'NotifyController@wechatPay')->name('notify_url.wechatpay');


Route::group(['prefix' => 'v1'], function () {
    Route::middleware(['app'])->group(function () {
        // 下单
        Route::post('/order', 'OrderController@store');

        // 查询
        Route::get('/order/query', 'OrderController@query');
    });
});
