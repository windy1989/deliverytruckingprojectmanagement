<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Vendor;
use App\Models\Customer;
use Barryvdh\DomPDF\PDF;
use App\Models\Transport;
use App\Models\Warehouse;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\OrderTransport;
use App\Models\OrderDestination;
use App\Models\OrderCustomerDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {

    public function index()
    {
        $data = [
            'title'     => 'Digitrans - Order',
            'customer'  => Customer::select('id', 'name')->where('status', 1)->get(),
            'vendor'    => Vendor::select('id', 'name')->where('status', 1)->get(),
            'transport' => Transport::where('status', 1)->get(),
            'warehouse' => Warehouse::all(),
            'unit'      => Unit::where('type', 1)->get(),
            'content'   => 'data.order'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function getDriver(Request $request)
    {
        $data = Driver::where('vendor_id', $request->vendor_id)->get();
        return response()->json($data);
    }

    public function getDestination(Request $request)
    {
        $data     = Destination::where('vendor_id', $request->vendor_id)->get();
        $response = [];

        foreach($data as $d) {
            $response[] = [
                'id'               => $d->id,
                'city_origin'      => $d->cityOrigin->name,
                'city_destination' => $d->cityDestination->name,
                'destination'      => $d->cityOrigin->name . ' &rarr; ' . $d->cityDestination->name
            ];
        }

        return response()->json($response);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'reference',
            'user_id',
            'customer_id',
            'vendor_id',
            'qty',
            'weight',
            'date',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Order::whereIn('status', [1,2])
            ->count();

        $query_data = Order::whereIn('status', [1,2])
            ->where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('reference', 'like', "%$search%")
                            ->orWhereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->groupBy('id')
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Order::whereIn('status',[1,2])
            ->where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('reference', 'like', "%$search%")
                            ->orWhereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('vendor', function($query) use ($search) {
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
                if($val->status == 1) {
                    $hide_action = '';
                } else {
                    $hide_action = 'style="display:none;"';
                }

                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->reference,
                    '<a href="' . url('master_data/setting/user') . '" class="text-primary">' . $val->user->name . '</a>',
                    $val->orderCustomerDetail->count(),
                    '<a href="' . url('master_data/vendor') . '" class="text-primary">' . $val->vendor->name . '</a>',
                    $val->qty . ' ' . $val->unit->name,
                    $val->weight . ' Kg',
                    $val->date,
                    $val->status(),
                    '
                        <a href="' . url('data/order/print/' . $val->id) . '" class="btn btn-success btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> Cetak</a>
                        <button type="button" class="btn btn-warning btn-sm" onclick="show(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="destroy(' . $val->id . ')" ' . $hide_action . '><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus</button>
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'customer_id' => 'required',
            'vendor_id'   => 'required',
            'unit_id'     => 'required',
            'weight'      => 'required',
            'qty'         => 'required',
            'date'        => 'required',
            'deadline'    => 'required',
            'tolerance'   => 'required'
        ], [
            'customer_id.required' => 'Mohon memilih customer.',
            'vendor_id.required'   => 'Mohon memilih vendor.',
            'unit_id.required'     => 'Mohon memilih satuan.',
            'weight.required'      => 'Mohon mengisi berat.',
            'qty.required'         => 'Mohon mengisi jumlah.',
            'date.required'        => 'Mohon mengisi tanggal.',
            'deadline.required'    => 'Mohon mengisi batas waktu.',
            'tolerance.required'   => 'Mohon mengisi toleransi.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Order::create([
                'user_id'     => session('id'),
                'vendor_id'   => $request->vendor_id,
                'unit_id'     => $request->unit_id,
                'code'        => Order::generateCode(),
                'reference'   => $request->reference,
                'weight'      => $request->weight,
                'qty'         => $request->qty,
                'date'        => $request->date,
                'deadline'    => $request->deadline,
                'tolerance'   => $request->tolerance,
                'status'      => 1
            ]);

            if($query) {
                foreach($request->customer_id as $key => $valcustomer) {
                    $addDataCustomer = OrderCustomerDetail::create([
                        'order_id'        => $query->id,
                        'customer_id' => $request->customer_id[$key],
                    ]);
                }

                if($request->has('destination_id')) {
                    foreach($request->destination_id as $val) {
                        OrderDestination::create([
                            'order_id'       => $query->id,
                            'destination_id' => $val
                        ]);
                    }
                }

                if($request->has('driver_id')) {
                    foreach($request->driver_id as $key => $val) {
                        OrderTransport::create([
                            'order_id'              => $query->id,
                            'driver_id'             => $val,
                            'transport_id'          => $request->transport_id[$key],
                            'warehouse_origin'      => $request->warehouse_origin[$key],
                            'warehouse_destination' => $request->warehouse_destination[$key]
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Order())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data order ' . $query->code);

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
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $data              = Order::find($request->id);
        $order_transport   = [];
        $order_destination = [];
        $customer_id       = [];

        foreach($data->orderTransport as $o) {
            $order_transport[] = [
                'driver_id'                  => $o->driver_id,
                'driver_name'                => $o->driver->name,
                'transport_id'               => $o->transport_id,
                'transport_brand'            => $o->transport->brand,
                'transport_no_plate'         => $o->transport->no_plate,
                'warehouse_origin'           => $o->warehouse_origin,
                'warehouse_origin_name'      => '(' . $o->warehouseOrigin->code . ') ' . $o->warehouseOrigin->name,
                'warehouse_destination'      => $o->warehouse_destination,
                'warehouse_destination_name' => '(' . $o->warehouseDestination->code . ') ' . $o->warehouseDestination->name
            ];
        }

        foreach($data->orderDestination as $o) {
            $order_destination[] = [
                'destination_id'   => $o->destination_id,
                'city_origin'      => $o->destination->cityOrigin->name,
                'city_destination' => $o->destination->cityDestination->name
            ];
        }

        if($data->orderCustomerDetail) {
            foreach($data->orderCustomerDetail as $ocd) {
                $customer_id[] = $ocd->customer_id;
            }
        }

        return response()->json([
            'customer_id'       => $customer_id,
            'vendor_id'         => $data->vendor_id,
            'unit_id'           => $data->unit_id,
            'code'              => $data->code,
            'reference'         => $data->reference,
            'weight'            => $data->weight,
            'qty'               => $data->qty,
            'date'              => $data->date,
            'deadline'          => $data->deadline,
            'tolerance'         => $data->tolerance,
            'order_destination' => $order_destination,
            'order_transport'   => $order_transport
        ]);
    }

    public function update(Request $request, $id)
    {
        $code = Order::find($id);
        $validation = Validator::make($request->all(), [
            'customer_id' => 'required',
            'vendor_id'   => 'required',
            'unit_id'     => 'required',
            'weight'      => 'required',
            'qty'         => 'required',
            'date'        => 'required',
            'deadline'    => 'required',
            'tolerance'   => 'required'
        ], [
            'customer_id.required' => 'Mohon memilih customer.',
            'vendor_id.required'   => 'Mohon memilih vendor.',
            'unit_id.required'     => 'Mohon memilih satuan.',
            'weight.required'      => 'Mohon mengisi berat.',
            'qty.required'         => 'Mohon mengisi jumlah.',
            'date.required'        => 'Mohon mengisi tanggal.',
            'deadline.required'    => 'Mohon mengisi batas waktu.',
            'tolerance.required'   => 'Mohon mengisi toleransi.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->has('status')) {
                $stats = 4;
            } else {
                $stats = $code->status;
            }

            $query = $code->update([
                'code'        => $request->code ? $request->code : $order->code,
                'vendor_id'   => $request->vendor_id,
                'unit_id'     => $request->unit_id,
                'reference'   => $request->reference,
                'weight'      => $request->weight,
                'qty'         => $request->qty,
                'date'        => $request->date,
                'deadline'    => $request->deadline,
                'tolerance'   => $request->tolerance,
                'status'      => $stats
            ]);

            if($query) {
                OrderCustomerDetail::where('order_id', $id)->delete();
                if($request->has('customer_id')) {
                    foreach($request->customer_id as $key => $valcustomer) {
                        OrderCustomerDetail::create([
                            'order_id'        => $id,
                            'customer_id' => $request->customer_id[$key],
                        ]);
                    }
                }

                OrderDestination::where('order_id', $id)->delete();
                if($request->has('destination_id')) {
                    foreach($request->destination_id as $val) {
                        OrderDestination::create([
                            'order_id'       => $id,
                            'destination_id' => $val
                        ]);
                    }
                }

                OrderTransport::where('order_id', $id)->delete();
                if($request->has('driver_id')) {
                    foreach($request->driver_id as $key => $val) {
                        OrderTransport::create([
                            'order_id'              => $id,
                            'driver_id'             => $val,
                            'transport_id'          => $request->transport_id[$key],
                            'warehouse_origin'      => $request->warehouse_origin[$key],
                            'warehouse_destination' => $request->warehouse_destination[$key]
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Order())
                    ->causedBy(session('id'))
                    ->log('Mengubah data order ' . $code->code);

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
        }

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        $order = Order::find($request->id);

        $query = Order::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Order())
                ->causedBy(session('id'))
                ->log('Menghapus data order ' . $order->code);

            $response = [
                'status'  => 200,
                'message' => 'Data telah dihapus.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data gagal dihapus.'
            ];
        }

        return response()->json($response);
    }

    public function print($id)
    {
        $order = Order::find($id);
        $data  = [
            'title'   => 'Digitrans - ' . $order->code,
            'order'   => $order,
            'content' => 'data.order_print'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function openPDF()
    {
        $order = Order::find($id);
        // usersPdf is the view that includes the downloading content
        $view = View::make('usersPdf', ['users'=>$users]);
        $html_content = $view->render();
        // Set title in the PDF
        PDF::SetTitle("Digitrans");
        PDF::AddPage();
        PDF::writeHTML($html_content, true, false, true, false, '');
        // userlist is the name of the PDF downloading
        PDF::Output('userlist.pdf');
    }

    public function savePDF()
    {
        $users = Order::find($id);
        $view = View::make('usersPdf', ['users'=>$users]);
        $html_content = $view->render();
        PDF::SetTitle("Digitrans");
        PDF::AddPage();
        PDF::writeHTML($html_content, true, false, true, false, '');
        // D is the change of these two functions. Including D parameter will avoid
        // loading PDF in browser and allows downloading directly
        PDF::Output('digitrans.pdf', 'D');
    }

}
