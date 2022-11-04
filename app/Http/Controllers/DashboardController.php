<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Order;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class DashboardController extends Controller {

   public function index()
   {
        $order = [
            'jan' => Order::whereMonth('created_at', '01')->whereYear('created_at', date('Y'))->count(),
            'feb' => Order::whereMonth('created_at', '02')->whereYear('created_at', date('Y'))->count(),
            'mar' => Order::whereMonth('created_at', '03')->whereYear('created_at', date('Y'))->count(),
            'apr' => Order::whereMonth('created_at', '04')->whereYear('created_at', date('Y'))->count(),
            'mei' => Order::whereMonth('created_at', '05')->whereYear('created_at', date('Y'))->count(),
            'jun' => Order::whereMonth('created_at', '06')->whereYear('created_at', date('Y'))->count(),
            'jul' => Order::whereMonth('created_at', '07')->whereYear('created_at', date('Y'))->count(),
            'agu' => Order::whereMonth('created_at', '08')->whereYear('created_at', date('Y'))->count(),
            'sep' => Order::whereMonth('created_at', '09')->whereYear('created_at', date('Y'))->count(),
            'okt' => Order::whereMonth('created_at', '10')->whereYear('created_at', date('Y'))->count(),
            'nov' => Order::whereMonth('created_at', '11')->whereYear('created_at', date('Y'))->count(),
            'des' => Order::whereMonth('created_at', '12')->whereYear('created_at', date('Y'))->count()
        ];

        $data = [
            'title'    => 'Digitrans - Dashboard',
            'order'    => $order,
            'content'  => 'dashboard'
        ];

        return view('layouts.index', ['data' => $data]);
   }

}
