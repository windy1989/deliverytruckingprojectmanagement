<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Kota',
            'content' => 'location.city'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'province_id',
            'name',
            'latitude',
            'longitude'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = City::count();

        $query_data = City::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('latitude', 'like', "%$search%")
                            ->orWhere('longitude', 'like', "%$search%")
                            ->orWhereHas('province', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = City::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('latitude', 'like', "%$search%")
                            ->orWhere('longitude', 'like', "%$search%")
                            ->orWhereHas('province', function($query) use ($search) {
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
                    '<a href="' . url('location/province') . '" class="text-primary">' . $val->province->name . '</a>',
                    $val->name,
                    $val->latitude,
                    $val->longitude,
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
            'province_id' => 'required',
            'name'        => 'required'
        ], [
            'province_id.required' => 'Mohon memilih provinsi.',
            'name.required'        => 'Mohon mengisi nama.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = City::create([
                'province_id' => $request->province_id,
                'name'        => $request->name,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude
            ]);

            if($query) {
                activity()
                    ->performedOn(new City())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data kota ' . $query->name);

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
        $data = City::find($request->id);
        return response()->json([
            'province_id'   => $data->province_id,
            'province_name' => $data->province->name,
            'name'          => $data->name,
            'latitude'      => $data->latitude,
            'longitude'     => $data->longitude
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'province_id' => 'required',
            'name'        => 'required'
        ], [
            'province_id.required' => 'Mohon memilih provinsi.',
            'name.required'        => 'Mohon mengisi nama.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = City::where('id', $id)->update([
                'province_id' => $request->province_id,
                'name'        => $request->name,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude
            ]);

            if($query) {
                activity()
                    ->performedOn(new City())
                    ->causedBy(session('id'))
                    ->log('Mengubah data kota');

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
        $query = City::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new City())
                ->causedBy(session('id'))
                ->log('Menghapus data kota');

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
