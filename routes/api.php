<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function() {
    Route::prefix('user')->group(function() {
        Route::post('login', 'UserController@login');
    });

    Route::prefix('customer')->group(function() {
        Route::post('/', 'CustomerController@index');
    });

    Route::prefix('driver')->group(function() {
        Route::post('/', 'DriverController@index');
    });

    Route::prefix('transport')->group(function() {
        Route::post('/', 'TransportController@index');
    });

    Route::prefix('vendor')->group(function() {
        Route::post('/', 'VendorController@index');
    });

    Route::prefix('destination')->group(function() {
        Route::post('/', 'DestinationController@index');
    });

    Route::prefix('warehouse')->group(function() {
        Route::post('/', 'WarehouseController@index');
    });

    Route::prefix('order')->group(function() {
        Route::post('/', 'OrderController@index');
        Route::post('create', 'OrderController@create');
        Route::post('update', 'OrderController@update');
    });

    Route::prefix('suratjalan')->group(function() {
        Route::post('/', 'LetterWayController@index');
        Route::post('create/{order_id}', 'LetterWayController@create');
        Route::post('show', 'LetterWayController@show');
        Route::post('update/{id}', 'LetterWayController@update');
        Route::post('check_finish_order', 'LetterWayController@checkFinishOrder');
        Route::post('finish', 'LetterWayController@finish');
        Route::post('destroy', 'LetterWayController@destroy');
    });

    Route::prefix('detailorder')->group(function() {
        Route::post('/', 'DetailOrderController@index');
    });
});
