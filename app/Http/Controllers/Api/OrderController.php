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

class OrderController extends Controller {

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
            ->latest()
            ->paginate($per_page);

        if($query) {
            if($query->total() > 0) {
                foreach($query as $q) {
                    $countTransport;
                    if($q->orderTransport) {
                        foreach($q->orderTransport as $key => $ot) {
                            $countTransport = $key + 1;
                            $transport[] = [
                                'driver_id'                  => $ot->driver_id,
                                'driver_name'                => $ot->driver->name,
                                'transport_id'               => $ot->transport_id,
                                'transport_brand'            => $ot->transport->brand,
                                'transport_no_plate'         => $ot->transport->no_plate,
                                'warehouse_origin'           => $ot->warehouse_origin,
                                'warehouse_origin_name'      => '(' . $ot->warehouseOrigin->code . ') ' . $ot->warehouseOrigin->name,
                                'warehouse_destination'      => $ot->warehouse_destination,
                                'warehouse_destination_name' => '(' . $ot->warehouseDestination->code . ') ' . $ot->warehouseDestination->name
                            ];
                        }
                    }
                    
                    $countTujuan;
                    if($q->orderDestination) {
                        foreach($q->orderDestination as $od) {
                            $countTujuan=$key+1;
                            $destination[] = [
                                'destination_id'   => $od->destination_id,
                                'city_origin'      => $od->destination->cityOrigin->name,
                                'city_destination' => $od->destination->cityDestination->name
                            ];
                        }
                    }

                    $countCust;
                    foreach ($q->orderCustomerDetail as $key => $value) {
                        $countCust=$key+1;
                        $customer[] = [
                            'id'   => $value->customer->id,
                            'name' => $value->customer->name,
                        ];
                    }

                    $result[] = [
                        'id'          => $q->id,
                        'user'        => $q->user->name,
                        'customer'    => $customer,
                        'jumlah_customer'    => $countCust,
                        'jumlah_transport'   => $countTransport,
                        'jumlah_destination' => $countTujuan,
                        'vendor_id'   => $q->vendor->id,
                        'vendor_name' => $q->vendor->name,
                        'code'        => $q->code,
                        'weight'      => $q->weight,
                        'qty'         => $q->qty,
                        'date'        => $q->date,
                        'deadline'    => $q->deadline,
                        'tolerance'   => $q->tolerance,
                        'status'      => $q->status(),
                        'created_at'  => $q->created_at,
                        'updated_at'  => $q->updated_at,
                        'transport'   => $transport,
                        'destination' => $destination
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id'        => 'required',
            'driver_id'      => 'required|array',
            'destination_id' => 'required|array',
            'customer_id'    => 'required',
            'vendor_id'      => 'required',
            'weight'         => 'required',
            'qty'            => 'required',
            'date'           => 'required',
            'deadline'       => 'required',
            'tolerance'      => 'required'
        ], [
            'user_id.required'        => 'Mohon mengisi user id.',
            'driver_id.required'      => 'Mohon mengisi driver_id.',
            'driver_id.array'         => 'driver_id harus bertipe data array .',
            'destination_id.required' => 'Mohon mengisi destination_id.',
            'destination_id.array'    => 'destination_id harus bertipe data array .',
            'customer_id.required'    => 'Mohon memilih customer.',
            'vendor_id.required'      => 'Mohon memilih vendor.',
            'weight.required'         => 'Mohon mengisi berat.',
            'qty.required'            => 'Mohon mengisi jumlah.',
            'date.required'           => 'Mohon mengisi tanggal.',
            'deadline.required'       => 'Mohon mengisi batas waktu.',
            'tolerance.required'      => 'Mohon mengisi toleransi.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Order::create([
                'user_id'     => $request->user_id,
                'vendor_id'   => $request->vendor_id,
                'code'        => Order::generateCode(),
                'weight'      => $request->weight,
                'qty'         => $request->qty,
                'date'        => $request->date,
                'deadline'    => $request->deadline,
                'tolerance'   => $request->tolerance,
                'status'      => 1
            ]);
            
            if($query) {
                if($request->has('customer_id')) {
                    foreach($request->customer_id as $key => $valcustomer) {
            
                        $addDataCustomer = OrderCustomerDetail::create([
                            'order_id'        => $query->id,
                            'customer_id'     => $valcustomer,
                        ]);
            
                        
                    }
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
                    ->causedBy($query->user_id)
                    ->withProperties($query)
                    ->log('Menambah data order ' . $query->code);

                $response = [
                    'status'  => 201,
                    'message' => 'Data successfully process.',
                    'result'  => $query
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data failed process.'
                ];
            }
        }

        return response()->json($response, $response['status']);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id'             => 'required',
            'user_id'        => 'required',
            'driver_id'      => 'required|array',
            'destination_id' => 'required|array',
            'customer_id'    => 'required',
            'vendor_id'      => 'required',
            'weight'         => 'required',
            'qty'            => 'required',
            'date'           => 'required',
            'deadline'       => 'required',
            'tolerance'      => 'required'
        ], [
            'id.required'             => 'Mohon mengisi id.',
            'user_id.required'        => 'Mohon mengisi user id.',
            'driver_id.required'      => 'Mohon mengisi driver_id.',
            'driver_id.array'         => 'driver_id harus bertipe data array .',
            'destination_id.required' => 'Mohon mengisi destination_id.',
            'destination_id.array'    => 'destination_id harus bertipe data array .',
            'customer_id.required'    => 'Mohon memilih customer.',
            'vendor_id.required'      => 'Mohon memilih vendor.',
            'weight.required'         => 'Mohon mengisi berat.',
            'qty.required'            => 'Mohon mengisi jumlah.',
            'date.required'           => 'Mohon mengisi tanggal.',
            'deadline.required'       => 'Mohon mengisi batas waktu.',
            'tolerance.required'      => 'Mohon mengisi toleransi.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Order::where('id', $request->id)->update([
                'vendor_id'   => $request->vendor_id,
                'weight'      => $request->weight,
                'qty'         => $request->qty,
                'date'        => $request->date,
                'deadline'    => $request->deadline,
                'tolerance'   => $request->tolerance
            ]);

            if($query) {
                OrderDestination::where('order_id', $request->id)->delete();
                if($request->has('destination_id')) {
                    foreach($request->destination_id as $val) {
                        OrderDestination::create([
                            'order_id'       => $request->id,
                            'destination_id' => $val
                        ]);
                    }
                }

                OrderCustomerDetail::where('order_id', $request->id)->delete();
                if($request->has('customer_id')) {
                    foreach($request->customer_id as $key => $valcustomer) {
                        OrderCustomerDetail::create([
                            'order_id'        => $request->id,
                            'customer_id'     => $valcustomer,
                        ]);
                    }
                }

                OrderTransport::where('order_id', $request->id)->delete();
                if($request->has('driver_id')) {
                    foreach($request->driver_id as $key => $val) {
                        OrderTransport::create([
                            'order_id'              => $request->id,
                            'driver_id'             => $val,
                            'transport_id'          => $request->transport_id[$key],
                            'warehouse_origin'      => $request->warehouse_origin[$key],
                            'warehouse_destination' => $request->warehouse_destination[$key]
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Order())
                    ->causedBy($request->user_id)
                    ->log('Mengubah data order');

                $response = [
                    'status'  => 200,
                    'message' => 'Data successfully process.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data failed process.'
                ];
            }
        }

        return response()->json($response, $response['status']);
    }

}
