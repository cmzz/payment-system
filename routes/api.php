<?php

use Illuminate\Support\Facades\Route;

// 同步回调
Route::any('/order/callback', 'CallbackController@index')->name('return_url');

// 异步通知
Route::any('/order/notify/alipay', 'NotifyController@index')->name('notify_url.alipay');
Route::any('/order/notify/qpay', 'NotifyController@index')->name('notify_url.qpay');
Route::any('/order/notify/wechatpay', 'NotifyController@index')->name('notify_url.wechatpay');


Route::group(['prefix' => 'v1'], function () {
    Route::middleware(['app'])->group(function () {
        // 下单
        Route::post('/order', 'OrderController@store');

        // 查询
        Route::get('/order/query', 'OrderController@query');
    });
});
