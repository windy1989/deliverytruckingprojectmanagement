<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'AuthController@login');
Route::post('do_login', 'AuthController@doLogin');
Route::get('lock_screen', 'AuthController@lockScreen');
Route::get('generatepdf/{id}', 'InvoiceController@generatePDF');

Route::middleware('protect.login')->group(function () {
    Route::get('dashboard', 'DashboardController@index');
    Route::match(['get', 'post'], 'profile', 'AuthController@profile');
    Route::get('logout', 'AuthController@logout');
    Route::get('activity_log', 'AuthController@activityLog');

    Route::prefix('file_manager')->group(function () {
        Route::match(['get', 'post'], '/', 'FileManagerController@index');
        Route::get('download', 'FileManagerController@download');
        Route::match(['get', 'post'], 'destroy/{id?}', 'FileManagerController@destroy');
    });

    Route::prefix('download')->group(function () {
        Route::get('excel', 'DownloadController@excel');
        Route::get('pdf', 'DownloadController@pdf');
    });

    Route::prefix('select2_serverside')->group(function () {
        Route::post('vendor', 'Select2Controller@vendor');
        Route::post('load', 'Select2Controller@load');
        Route::post('order/{status?}', 'Select2Controller@order');
        Route::post('province', 'Select2Controller@province');
        Route::post('city/{province_id?}', 'Select2Controller@city');
    });

    Route::prefix('master_data')->group(function () {
        Route::prefix('warehouse')->middleware('role.access:master_data/warehouse')->group(function () {
            Route::get('/', 'WarehouseController@index');
            Route::post('datatable', 'WarehouseController@datatable');
            Route::post('create', 'WarehouseController@create');
            Route::post('show', 'WarehouseController@show');
            Route::post('update/{id}', 'WarehouseController@update');
            Route::post('destroy', 'WarehouseController@destroy');
        });

        Route::prefix('driver')->middleware('role.access:master_data/driver')->group(function () {
            Route::get('/', 'DriverController@index');
            Route::post('datatable', 'DriverController@datatable');
            Route::post('create', 'DriverController@create');
            Route::post('show', 'DriverController@show');
            Route::post('update/{id}', 'DriverController@update');
            Route::post('destroy', 'DriverController@destroy');
        });

        Route::prefix('transport')->middleware('role.access:master_data/transport')->group(function () {
            Route::get('/', 'TransportController@index');
            Route::post('datatable', 'TransportController@datatable');
            Route::post('create', 'TransportController@create');
            Route::post('show', 'TransportController@show');
            Route::post('update/{id}', 'TransportController@update');
            Route::post('destroy', 'TransportController@destroy');
        });

        Route::prefix('vendor')->middleware('role.access:master_data/vendor')->group(function () {
            Route::get('/', 'VendorController@index');
            Route::post('datatable', 'VendorController@datatable');
            Route::post('create', 'VendorController@create');
            Route::post('show', 'VendorController@show');
            Route::post('update/{id}', 'VendorController@update');
            Route::post('destroy', 'VendorController@destroy');
        });

        Route::prefix('destination')->middleware('role.access:master_data/destination')->group(function () {
            Route::get('/', 'DestinationController@index');
            Route::post('datatable', 'DestinationController@datatable');
            Route::post('create', 'DestinationController@create');
            Route::post('show', 'DestinationController@show');
            Route::post('update/{id}', 'DestinationController@update');
            Route::post('destroy', 'DestinationController@destroy');
        });

        Route::prefix('customer')->middleware('role.access:master_data/customer')->group(function () {
            Route::get('/', 'CustomerController@index');
            Route::post('datatable', 'CustomerController@datatable');
            Route::post('create', 'CustomerController@create');
            Route::post('show', 'CustomerController@show');
            Route::post('update/{id}', 'CustomerController@update');
            Route::post('destroy', 'CustomerController@destroy');
        });

        Route::prefix('unit')->middleware('role.access:master_data/unit')->group(function () {
            Route::get('/', 'UnitController@index');
            Route::post('datatable', 'UnitController@datatable');
            Route::post('create', 'UnitController@create');
            Route::post('show', 'UnitController@show');
            Route::post('update/{id}', 'UnitController@update');
            Route::post('destroy', 'UnitController@destroy');
        });
    });

    Route::prefix('location')->group(function () {
        Route::prefix('province')->middleware('role.access:location/province')->group(function () {
            Route::get('/', 'ProvinceController@index');
            Route::post('datatable', 'ProvinceController@datatable');
            Route::post('create', 'ProvinceController@create');
            Route::post('show', 'ProvinceController@show');
            Route::post('update/{id}', 'ProvinceController@update');
            Route::post('destroy', 'ProvinceController@destroy');
        });

        Route::prefix('city')->middleware('role.access:location/city')->group(function () {
            Route::get('/', 'CityController@index');
            Route::post('datatable', 'CityController@datatable');
            Route::post('create', 'CityController@create');
            Route::post('show', 'CityController@show');
            Route::post('update/{id}', 'CityController@update');
            Route::post('destroy', 'CityController@destroy');
        });
    });

    Route::prefix('data')->group(function () {
        Route::prefix('order')->middleware('role.access:data/order')->group(function () {
            Route::get('/', 'OrderController@index');
            Route::post('datatable', 'OrderController@datatable');
            Route::post('get_driver', 'OrderController@getDriver');
            Route::post('get_destination', 'OrderController@getDestination');
            Route::post('create', 'OrderController@create');
            Route::post('show', 'OrderController@show');
            Route::post('update/{id}', 'OrderController@update');
            Route::post('destroy', 'OrderController@destroy');
            Route::get('print/{id}', 'OrderController@print');
        });

        Route::prefix('letter_way')->middleware('role.access:data/letter_way')->group(function () {
            Route::get('/', 'LetterWayController@index');
            Route::post('get_destination', 'LetterWayController@getDestination');
            Route::post('load_data', 'LetterWayController@loadData');
            Route::post('create/{order_id}', 'LetterWayController@create');
            Route::post('show', 'LetterWayController@show');
            Route::post('update/{id}', 'LetterWayController@update');
            Route::post('check_finish_order', 'LetterWayController@checkFinishOrder');
            Route::post('finish', 'LetterWayController@finish');
            Route::post('destroy', 'LetterWayController@destroy');
        });
    });

    Route::prefix('sales')->group(function () {
        Route::prefix('invoice')->middleware('role.access:sales/invoice')->group(function () {
            Route::get('/', 'InvoiceController@index');
            Route::post('datatable', 'InvoiceController@datatable');
            Route::post('get_letter_way', 'InvoiceController@getLetterWay');
            Route::get('total_invoice', 'InvoiceController@totalInvoice');
            Route::match(['get', 'post'], 'create', 'InvoiceController@create');
            Route::get('detail/{id}', 'InvoiceController@detail');
            Route::post('destroy', 'InvoiceController@destroy');
        });

        Route::prefix('receipt')->middleware('role.access:sales/receipt')->group(function () {
            Route::get('/', 'ReceiptController@index');
            Route::post('datatable', 'ReceiptController@datatable');
            Route::get('grandtotal', 'ReceiptController@grandtotal');
            Route::post('get_invoice', 'ReceiptController@getInvoice');
            Route::get('total_receipt', 'ReceiptController@totalReceipt');
            Route::match(['get', 'post'], 'create', 'ReceiptController@create');
            Route::match(['get', 'post'], 'claim/{id}', 'ReceiptController@claim');
            Route::post('add_picture', 'ReceiptController@addPicture');
            Route::get('get_picture', 'ReceiptController@getPicture');
            Route::post('destroy_picture', 'ReceiptController@destroyPicture');
            Route::post('paid', 'ReceiptController@paid');
            Route::get('detail/{id}', 'ReceiptController@detail');
            Route::post('destroy', 'ReceiptController@destroy');
        });
    });

    Route::prefix('purchase')->middleware('role.access:purchase')->group(function () {
        Route::get('/', 'PurchaseController@index');
        Route::post('datatable', 'PurchaseController@datatable');
        Route::get('grandtotal', 'PurchaseController@grandtotal');
        Route::post('get_letter_way', 'PurchaseController@getLetterWay');
        Route::get('total_repayment', 'PurchaseController@totalRepayment');
        Route::match(['get', 'post'], 'create', 'PurchaseController@create');
        Route::match(['get', 'post'], 'claim/{id}', 'PurchaseController@claim');
        Route::post('add_picture', 'PurchaseController@addPicture');
        Route::get('get_picture', 'PurchaseController@getPicture');
        Route::post('destroy_picture', 'PurchaseController@destroyPicture');
        Route::post('paid', 'PurchaseController@paid');
        Route::get('detail/{id}', 'PurchaseController@detail');
    });

    Route::prefix('accounting')->group(function () {
        Route::prefix('coa')->middleware('role.access:accounting/coa')->group(function () {
            Route::get('/', 'CoaController@index');
            Route::post('get_parent', 'CoaController@getParent');
            Route::post('get_sub', 'CoaController@getSub');
            Route::post('get_latest_sub', 'CoaController@getLatestSub');
            Route::post('datatable', 'CoaController@datatable');
            Route::post('create', 'CoaController@create');
            Route::post('show', 'CoaController@show');
            Route::post('update/{id}', 'CoaController@update');
            Route::post('destroy', 'CoaController@destroy');
        });

        Route::prefix('fee')->middleware('role.access:accounting/fee')->group(function () {
            Route::get('/', 'FeeController@index');
            Route::post('datatable', 'FeeController@datatable');
            Route::post('create', 'FeeController@create');
            Route::post('destroy', 'FeeController@destroy');
        });

        Route::prefix('journal')->middleware('role.access:accounting/journal')->group(function () {
            Route::get('/', 'JournalController@index');
        });

        Route::prefix('profit_loss')->middleware('role.access:accounting/profit_loss')->group(function () {
            Route::get('/', 'ProfitLossController@index');
        });

        Route::prefix('balance_sheet')->middleware('role.access:accounting/balance_sheet')->group(function () {
            Route::get('/', 'BalanceSheetController@index');
        });

        Route::prefix('cashflow')->middleware('role.access:accounting/cashflow')->group(function () {
            Route::get('/', 'CashflowController@index');
        });
    });

    Route::prefix('report')->group(function () {
        Route::prefix('order')->middleware('role.access:report/order')->group(function () {
            Route::get('/', 'ReportOrderController@index');
            Route::post('datatable', 'ReportOrderController@datatable');
            Route::post('show', 'ReportOrderController@show');
            Route::post('restore', 'ReportOrderController@restore');
        });

        Route::prefix('letter_way')->middleware('role.access:report/letter_way')->group(function () {
            Route::get('/', 'ReportLetterWayController@index');
            Route::post('datatable', 'ReportLetterWayController@datatable');
            Route::post('show', 'ReportLetterWayController@show');
        });

        Route::prefix('invoice')->middleware('role.access:report/invoice')->group(function () {
            Route::get('/', 'ReportInvoiceController@index');
            Route::post('datatable', 'ReportInvoiceController@datatable');
            Route::get('detail/{id}', 'ReportInvoiceController@detail');
        });

        Route::prefix('receipt')->middleware('role.access:report/receipt')->group(function () {
            Route::get('/', 'ReportReceiptController@index');
            Route::post('datatable', 'ReportReceiptController@datatable');
            Route::get('reception/{id}', 'ReportReceiptController@reception');
            Route::get('claim/{id}', 'ReportReceiptController@claim');
            Route::get('detail/{id}', 'ReportReceiptController@detail');
        });

        Route::prefix('purchase')->middleware('role.access:report/purchase')->group(function () {
            Route::get('/', 'ReportPurchaseController@index');
            Route::post('datatable', 'ReportPurchaseController@datatable');
            Route::get('claim/{id}', 'ReportPurchaseController@claim');
            Route::get('detail/{id}', 'ReportPurchaseController@detail');
        });

        Route::prefix('activity_log')->middleware('role.access:report/activity_log')->group(function () {
            Route::get('/', 'ReportActivityLogController@index');
        });

        Route::prefix('warehouse')->middleware('role.access:report/warehouse')->group(function () {
            Route::get('/', 'ReportWarehouseController@index');
            Route::post('datatable', 'ReportWarehouseController@datatable');
        });

        Route::prefix('vendor')->middleware('role.access:report/vendor')->group(function () {
            Route::get('/', 'ReportVendorController@index');
            Route::post('datatable', 'ReportVendorController@datatable');
        });

        Route::prefix('driver')->middleware('role.access:report/driver')->group(function () {
            Route::get('/', 'ReportDriverController@index');
            Route::post('datatable', 'ReportDriverController@datatable');
            Route::post('show', 'ReportDriverController@show');
        });

        Route::prefix('transport')->middleware('role.access:report/transport')->group(function () {
            Route::get('/', 'ReportTransportController@index');
            Route::post('datatable', 'ReportTransportController@datatable');
        });

        Route::prefix('destination')->middleware('role.access:report/destination')->group(function () {
            Route::get('/', 'ReportDestinationController@index');
            Route::post('datatable', 'ReportDestinationController@datatable');
            Route::post('show', 'ReportDestinationController@show');
        });

        Route::prefix('customer')->middleware('role.access:report/customer')->group(function () {
            Route::get('/', 'ReportCustomerController@index');
            Route::post('datatable', 'ReportCustomerController@datatable');
            Route::post('show', 'ReportCustomerController@show');
        });

        Route::prefix('unit')->middleware('role.access:report/unit')->group(function () {
            Route::get('/', 'ReportUnitController@index');
            Route::post('datatable', 'ReportUnitController@datatable');
        });

        Route::prefix('recap')->middleware('role.access:report/recap')->group(function () {
            Route::get('/', 'ReportRecapController@index');
            Route::post('datatable', 'ReportRecapController@datatable');
            Route::get('detail', 'ReportRecapController@detail');
            Route::get('detail_vendor', 'ReportRecapController@detailVendor');
            Route::get('print', 'ReportRecapController@print');
            Route::get('print_vendor', 'ReportRecapController@printVendor');
        });
    });

    Route::prefix('setting')->group(function () {
        Route::prefix('menu')->middleware('role.access:setting/menu')->group(function () {
            Route::get('/', 'MenuController@index');
            Route::post('get_parent', 'MenuController@getParent');
            Route::post('get_sub', 'MenuController@getSub');
            Route::post('datatable', 'MenuController@datatable');
            Route::post('create', 'MenuController@create');
            Route::post('show', 'MenuController@show');
            Route::post('update/{id}', 'MenuController@update');
            Route::post('destroy', 'MenuController@destroy');
        });

        Route::prefix('role')->middleware('role.access:setting/role')->group(function () {
            Route::get('/', 'RoleController@index');
            Route::post('datatable', 'RoleController@datatable');
            Route::post('create', 'RoleController@create');
            Route::post('show', 'RoleController@show');
            Route::post('update/{id}', 'RoleController@update');
            Route::post('destroy', 'RoleController@destroy');
        });

        Route::prefix('user')->middleware('role.access:setting/user')->group(function () {
            Route::get('/', 'UserController@index');
            Route::post('datatable', 'UserController@datatable');
            Route::post('create', 'UserController@create');
            Route::post('show', 'UserController@show');
            Route::post('update/{id}', 'UserController@update');
            Route::post('destroy', 'UserController@destroy');
            Route::post('reset_password', 'UserController@resetPassword');
        });
    });
});
