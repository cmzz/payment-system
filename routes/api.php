<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    // 同步回调
    Route::any('/order/callback', 'CallbackController@index')->name('return_url');

    // 异步通知
    Route::any('/order/notify', 'NotifyController@index')->name('notify_url');

    Route::middleware(['app'])->group(function () {
        // 下单
        Route::post('/order', 'OrderController@store');

        // 查询
        Route::get('/order/query', 'OrderController@query');
    });
});
