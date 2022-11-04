<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderPrintController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Cetak Order',
            'order'   => Order::where('status', 1)->latest()->get(),
            'content' => 'data.print'
        ];
        
        return view('layouts.index', ['data' => $data]);
    }

}
