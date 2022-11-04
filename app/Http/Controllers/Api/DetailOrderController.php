<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Driver;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\OrderCustomerDetail;
use Illuminate\Http\Request;
use App\Models\OrderTransport;
use App\Models\OrderDestination;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DetailOrderController extends Controller {

    public function index(Request $request)
    {
        $result      = [];
        $transport   = [];
        $vendors     = [];
        $destination = [];
        $customer    = [];
        $per_page    = 10;
        $query       = Order::where(function($query) use ($request) {
                if($request->id) {
                    $query->where('id', $request->id);
                }
            })
            
            ->orderBy('date','desc')
            ->take(10)
            ->latest()
            ->paginate($per_page);

        if($query) {
            if($query->total() > 0) {
                foreach($query as $q) {
                    $countTransport;
                    
                    if($q->orderTransport) {
                        foreach($q->orderTransport as $key => $ot) {
                            $countTransport = $key + 1;
                        }
                    }
                    $countTujuan;
                    if($q->orderDestination) {
                        foreach($q->orderDestination as $key => $od) {
                            $countTujuan=$key+1;
                        }
                    }

                    $countCust;
                    foreach($q->orderCustomerDetail as $key => $char)
                    {
                        $countCust=$key+1;
                    }

                    $result[] = [
                        'id'                 => $q->id,
                        'jumlah_customer'    => $countCust,
                        'code'               => $q->code,
                        'date'               => $q->date,
                        'status'             => $q->status(),
                        'jumlah_transport'   => $countTransport,
                        'jumlah_destination' => $countTujuan
                    ];
                }

                $response = [
                    'status'  => 200,
                    'message' => 'Data found',
                    'result'  => [
                        'count'      => $query->count(),
                        'page'       => $query->currentPage(),
                        'total_page' => ceil($query->total() / $per_page),
                        'total_data' => $query->total(),
                        'data'       => $result
                    ]
                ];
            } else {
                $response = [
                    'status'  => 404,
                    'message' => 'Data not found'
                ];
            }
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Server error'
            ];
        }

        return response()->json($response, $response['status']);
    }

}
