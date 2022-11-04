<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\DestinationPrice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Harga Per Tujuan',
            'vendor'  => Vendor::select('id', 'name')->where('status', 1)->get(),
            'city'    => City::all(),
            'unit'    => Unit::where('type', 1)->get(),
            'content' => 'master_data.destination'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'vendor_id',
            'label',
            'city_origin',
            'city_destination',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Destination::count();

        $query_data = Destination::where(function ($query) use ($search, $request) {
                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('label', 'like', "%$search%")
                            ->orWhereHas('vendor', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Destination::where(function ($query) use ($search, $request) {
                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('label', 'like', "%$search%")
                            ->orWhereHas('vendor', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach ($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    '<a href="' . url('master_data/vendor') . '" class="text-primary">' . $val->vendor->name . '</a>',
                    $val->label,
                    '<a href="' . url('location/city') . '" class="text-primary">' . $val->cityOrigin->name . '</a>',
                    '<a href="' . url('location/city') . '" class="text-primary">' . $val->cityDestination->name . '</a>',
                    '
                        <button type="button" class="btn btn-warning btn-sm" onclick="show(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="destroy(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus</button>
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
            'vendor_id'        => 'required',
            'city_origin'      => 'required',
            'city_destination' => 'required'
        ], [
            'vendor_id.required'        => 'Mohon memilih vendor.',
            'city_origin.required'      => 'Mohon memilih kota asal.',
            'city_destination.required' => 'Mohon memilih kota tujuan.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Destination::create([
                'vendor_id'        => $request->vendor_id,
                'label'            => $request->label,
                'city_origin'      => $request->city_origin,
                'city_destination' => $request->city_destination
            ]);

            if($query) {
                if($request->has('date')) {
                    foreach($request->date as $key => $val) {
                        DestinationPrice::create([
                            'destination_id' => $query->id,
                            'unit_id'        => $request->unit_id[$key],
                            'date'           => $val,
                            'price_vendor'   => $request->price_vendor[$key],
                            'price_customer' => $request->price_customer[$key]
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Destination())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data tujuan');

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
        $data              = Destination::find($request->id);
        $destination_price = [];

        foreach($data->destinationPrice as $d) {
            $destination_price[] = [
                'unit_id'        => $d->unit_id,
                'unit_name'      => $d->unit->name,
                'date'           => $d->date,
                'price_vendor'   => 'Rp.' . number_format($d->price_vendor, 2, ',', '.'),
                'price_customer' => 'Rp.' . number_format($d->price_customer, 2, ',', '.'),
            ];
        }

        return response()->json([
            'vendor_id'         => $data->vendor_id,
            'label'             => $data->label,
            'city_origin'       => $data->city_origin,
            'city_destination'  => $data->city_destination,
            'destination_price' => $destination_price
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'vendor_id'        => 'required',
            'city_origin'      => 'required',
            'city_destination' => 'required'
        ], [
            'vendor_id.required'        => 'Mohon memilih vendor.',
            'city_origin.required'      => 'Mohon memilih kota asal.',
            'city_destination.required' => 'Mohon memilih kota tujuan.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Destination::where('id', $id)->update([
                'vendor_id'        => $request->vendor_id,
                'label'            => $request->label,
                'city_origin'      => $request->city_origin,
                'city_destination' => $request->city_destination
            ]);

            if($query) {
                if($request->has('date')) {
                    DestinationPrice::where('destination_id', $id)->delete();
                    foreach($request->date as $key => $val) {
                        DestinationPrice::create([
                            'destination_id' => $id,
                            'unit_id'        => $request->unit_id[$key],
                            'date'           => $val,
                            'price_vendor'   => $request->price_vendor[$key],
                            'price_customer' => $request->price_customer[$key]
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Destination())
                    ->causedBy(session('id'))
                    ->log('Mengubah data tujuan');

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
        $query = Destination::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Destination())
                ->causedBy(session('id'))
                ->log('Menghapus data tujuan');

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

}
