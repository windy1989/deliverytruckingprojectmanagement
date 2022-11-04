<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportCustomerController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Laporan Customer',
            'content' => 'report.customer'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'name',
            'phone',
            'city_id',
            'pic',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Customer::count();

        $query_data = Customer::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('pic', 'like', "%$search%")
                            ->orWhereHas('city', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Customer::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('pic', 'like', "%$search%")
                            ->orWhereHas('city', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->name,
                    $val->phone,
                    '<a href="' . url('location/city') . '" class="text-primary">' . $val->city->name . '</a>',
                    $val->pic,
                    $val->status(),
                    '
                        <button type="button" class="btn btn-info btn-sm" onclick="show(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Detail</button>
                    '
                ];
                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $data     = Customer::find($request->id);
        $bill     = $data->customerBill;
        $arr_bill = [];

        if($bill) {
            foreach($bill as $b) {
                $arr_bill[] = [
                    'bank' => $b->bank,
                    'bill' => $b->bill
                ];
            }
        }

        return response()->json([
            'city'    => $data->city->name,
            'code'    => $data->code,
            'name'    => $data->name,
            'phone'   => $data->phone,
            'fax'     => $data->fax,
            'address' => $data->address,
            'pic'     => $data->pic,
            'status'  => $data->status(),
            'bill'    => $arr_bill
        ]);
    }

}
