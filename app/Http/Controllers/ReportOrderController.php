<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class ReportOrderController extends Controller {

    public function index()
    {
        $data = [
            'title'    => 'Digitrans - Laporan Order',
            'customer' => Customer::select('id', 'name')->where('status', 1)->get(),
            'vendor'   => Vendor::select('id', 'name')->where('status', 1)->get(),
            'content'  => 'report.order'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'reference',
            'customer_id',
            'vendor_id',
            'status',
            'date'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Order::count();

        $query_data = Order::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('reference', 'like', "%$search%")
                            ->orWhereHas('orderCustomerDetail', function($query) use ($search) {
                                $query->whereHas('customer', function($query) use ($search) {
                                    $query->where('name', 'like', "%$search%");
                                });
                            })
                            ->orWhereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereBetween('date', [$request->start_date, $request->finish_date]);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }

                if($request->customer_id) {
                    $query->whereHas('orderCustomerDetail', function($query) use ($request) {
                        $query->where('customer_id', $request->customer_id);
                    });
                }

                if($request->vendor_id) {
                    $query->where('vendor_id', $request->vendor_id);
                }

                if($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Order::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('reference', 'like', "%$search%")
                            ->orWhereHas('orderCustomerDetail', function($query) use ($search) {
                                $query->whereHas('customer', function($query) use ($search) {
                                    $query->where('name', 'like', "%$search%");
                                });
                            })
                            ->orWhereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereBetween('date', [$request->start_date, $request->finish_date]);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }

                if($request->customer_id) {
                    $query->whereHas('orderCustomerDetail', function($query) use ($request) {
                        $query->where('customer_id', $request->customer_id);
                    });
                }

                if($request->vendor_id) {
                    $query->where('vendor_id', $request->vendor_id);
                }

                if($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $cust_nm = '';
                foreach($val->orderCustomerDetail as $key => $char) {
                    $cust_nm .= $char->customer->name;
                }

                if($val->status == 3) {
                    $button = '<button type="button" class="btn btn-warning btn-sm" onclick="restore(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Restore</button>';
                } else {
                    $button = '<button type="button" class="btn btn-info btn-sm" onclick="show(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Detail</button>';
                }

                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->reference,
                    $cust_nm,
                    $val->vendor->name,
                    $val->date,
                    $val->status(),
                    $button
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
        $data              = Order::find($request->id);
        $order_transport   = [];
        $order_destination = [];
        $order_detail      = [];
        $cust_nm           = '';

        foreach($data->orderTransport as $o) {
            if($o->warehouseOrigin) {
                $warehouse_origin = '(' . $o->warehouseOrigin->code . ') ' . $o->warehouseOrigin->name;
            } else {
                $warehouse_origin = '-';
            }

            if($o->warehouseDestination) {
                $warehouse_destination = '(' . $o->warehouseDestination->code . ') ' . $o->warehouseDestination->name;
            } else {
                $warehouse_destination = '-';
            }

            $order_transport[] = [
                'driver_name'                => $o->driver->name,
                'transport_brand'            => $o->transport->brand,
                'transport_no_plate'         => $o->transport->no_plate,
                'warehouse_origin_name'      => $warehouse_origin,
                'warehouse_destination_name' => $warehouse_destination
            ];
        }

        foreach($data->orderCustomerDetail as $key => $char) {
            $cust_nm .= $char->customer->name;
        }

        foreach($data->orderDestination as $o) {
            $order_destination[] = [
                'city_origin'      => $o->destination->cityOrigin->name,
                'city_destination' => $o->destination->cityDestination->name
            ];
        }

        return response()->json([
            'customer_id'       => ': ' . $cust_nm,
            'vendor_id'         => ': ' . $data->vendor->name,
            'user_id'           => ': ' . $data->user->name,
            'code'              => ': ' . $data->code,
            'reference'         => ': ' . $data->reference,
            'weight'            => ': ' . $data->weight . ' Kg',
            'qty'               => ': ' . $data->qty . ' ' . $data->unit->name,
            'date'              => ': ' . date('d-m-Y', strtotime($data->date)),
            'deadline'          => ': ' . $data->deadline . ' Hari',
            'tolerance'         => ': ' . $data->tolerance . '%',
            'created_at'        => ': ' . date('d-m-Y', strtotime($data->created_at)),
            'order_destination' => $order_destination,
            'order_transport'   => $order_transport
        ]);
    }

    public function restore(Request $request)
    {
        $code = Order::find($request->id);
        $stats = 1;
        $query = $code->update([
            'status'      => $stats
        ]);

        if($query) {

            activity()
                ->performedOn(new Order())
                ->causedBy(session('id'))
                ->log('Restore data order ' . $code->code);

            $response = [
                'status'  => 200,
                'message' => 'Data telah diproses.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data gagal diproses.'
            ];
        }


        return response()->json($response);
    }
}
