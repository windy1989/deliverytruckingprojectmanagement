<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Customer',
            'content' => 'master_data.customer'
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

        $query_data = Customer::where(function ($query) use ($search) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('pic', 'like', "%$search%")
                        ->orWhereHas('city', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Customer::where(function ($query) use ($search) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('pic', 'like', "%$search%")
                        ->orWhereHas('city', function ($query) use ($search) {
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
                    $val->code,
                    $val->name,
                    $val->phone,
                    '<a href="' . url('location/city') . '" class="text-primary">' . $val->city->name . '</a>',
                    $val->pic,
                    $val->status(),
                    '
                        <button type="button" class="btn btn-warning btn-sm" onclick="show(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="destroy(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus</button>
                    '
                ];
                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if ($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if ($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'city_id' => 'required',
            'name'    => 'required',
            'status'  => 'required'
        ], [
            'city_id.required' => 'Mohon memilih kota.',
            'name.required'    => 'Mohon mengisi nama.',
            'status.required'  => 'Mohon memilih status.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Customer::create([
                'city_id' => $request->city_id,
                'code'    => Customer::generateCode(),
                'name'    => $request->name,
                'phone'   => $request->phone,
                'fax'     => $request->fax,
                'website' => $request->website,
                'address' => $request->address,
                'pic'     => $request->pic,
                'warning_date_vendor' => $request->warning_date_vendor,
                'danger_date_vendor'  => $request->danger_date_vendor,
                'warning_date_ttbr' => $request->warning_date_ttbr,
                'danger_date_ttbr'  => $request->danger_date_ttbr,
                'status'  => $request->status
            ]);

            if ($query) {
                if ($request->has('bank')) {
                    foreach ($request->bank as $key => $b) {
                        CustomerBill::create([
                            'customer_id' => $query->id,
                            'bank'        => $b,
                            'bill'        => $request->bill[$key]
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Customer())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data customer ' . $query->code);

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
        $data = Customer::find($request->id);
        return response()->json([
            'city_id'   => $data->city_id,
            'city_name' => $data->city->name,
            'code'      => $data->code,
            'name'      => $data->name,
            'phone'     => $data->phone,
            'fax'       => $data->fax,
            'address'   => $data->address,
            'pic'       => $data->pic,
            'warning_date_vendor' => $data->warning_date_vendor,
            'danger_date_vendor'  => $data->danger_date_vendor,
            'warning_date_ttbr' => $data->warning_date_ttbr,
            'danger_date_ttbr'  => $data->danger_date_ttbr,
            'status'    => $data->status,
            'bill'      => $data->customerBill
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'city_id' => 'required',
            'name'    => 'required',
            'status'  => 'required'
        ], [
            'city_id.required' => 'Mohon memilih kota.',
            'name.required'    => 'Mohon mengisi nama.',
            'status.required'  => 'Mohon memilih status.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Customer::where('id', $id)->update([
                'city_id' => $request->city_id,
                'name'    => $request->name,
                'phone'   => $request->phone,
                'fax'     => $request->fax,
                'website' => $request->website,
                'address' => $request->address,
                'pic'     => $request->pic,
                'warning_date_vendor' => $request->warning_date_vendor,
                'danger_date_vendor'  => $request->danger_date_vendor,
                'warning_date_ttbr' => $request->warning_date_ttbr,
                'danger_date_ttbr'  => $request->danger_date_ttbr,
                'status'  => $request->status
            ]);

            if ($query) {
                if ($request->has('bank')) {
                    CustomerBill::where('customer_id', $id)->delete();
                    foreach ($request->bank as $key => $b) {
                        CustomerBill::create([
                            'customer_id' => $id,
                            'bank'        => $b,
                            'bill'        => $request->bill[$key]
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Customer())
                    ->causedBy(session('id'))
                    ->log('Mengubah data customer');

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
        $query = Customer::where('id', $request->id)->delete();
        if ($query) {
            activity()
                ->performedOn(new Customer())
                ->causedBy(session('id'))
                ->log('Menghapus data customer');

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
