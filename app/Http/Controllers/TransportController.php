<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TransportController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Kendaraan',
            'content' => 'master_data.transport'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'photo_stnk',
            'no_plate',
            'brand',
            'valid_kir',
            'valid_stnk',
            'type',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Transport::count();

        $query_data = Transport::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('no_plate', 'like', "%$search%")
                            ->orWhere('brand', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Transport::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('no_plate', 'like', "%$search%")
                            ->orWhere('brand', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $photo_stnk = '<a href="' . $val->photoStnk() . '" data-lightbox="Transport_' . $val->id . '" data-title="' . $val->name . '"><img src="' . $val->photoStnk() . '" class="img img-responsive" style="max-width:50px; max-height:50px;"></a>';

                $response['data'][] = [
                    $nomor,
                    $photo_stnk,
                    $val->no_plate,
                    $val->brand,
                    $val->valid_kir,
                    $val->valid_stnk,
                    $val->type,
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
            'no_plate'   => 'required',
            'brand'      => 'required',
            'photo_stnk' => 'max:2048|mimes:jpeg,jpg,png',
            'type'       => 'required',
            'status'     => 'required'
        ], [
            'no_plate.required' => 'Mohon mengisi nomor plat.',
            'brand.required'    => 'Mohon mengisi merk.',
            'photo_stnk.max'    => 'Foto STNK maksimal 2MB.',
            'photo_stnk.mimes'  => 'Foto STNK harus berformat jpeg, jpg, png.',
            'type.required'     => 'Mohon mengisi jenis.',
            'status.required'   => 'Mohon mengisi status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $photo_stnk = $request->file('photo_stnk') ? $request->file('photo_stnk')->store('public/transport') : null;

            $query = Transport::create([
                'no_plate'   => $request->no_plate,
                'brand'      => $request->brand,
                'valid_kir'  => $request->valid_kir,
                'photo_stnk' => $photo_stnk,
                'valid_stnk' => $request->valid_stnk,
                'type'       => $request->type,
                'status'     => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Transport())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data kendaraan ' . $query->brand);

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
        $data = Transport::find($request->id);

        return response()->json([
            'no_plate'   => $data->no_plate,
            'brand'      => $data->brand,
            'valid_kir'  => $data->valid_kir,
            'photo_stnk' => $data->photoStnk(),
            'valid_stnk' => $data->valid_stnk,
            'type'       => $data->type,
            'status'     => $data->status
        ]);
    }

    public function update(Request $request, $id)
    {
        $data       = Transport::find($id);
        $validation = Validator::make($request->all(), [
            'no_plate'   => 'required',
            'brand'      => 'required',
            'photo_stnk' => 'max:2048|mimes:jpeg,jpg,png',
            'type'       => 'required',
            'status'     => 'required'
        ], [
            'no_plate.required' => 'Mohon mengisi nomor plat.',
            'brand.required'    => 'Mohon mengisi merk.',
            'photo_stnk.max'    => 'Foto STNK maksimal 2MB.',
            'photo_stnk.mimes'  => 'Foto STNK harus berformat jpeg, jpg, png.',
            'type.required'     => 'Mohon mengisi jenis.',
            'status.required'   => 'Mohon mengisi status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->has('photo_stnk')) {
                if(Storage::exists($data->photo_stnk)) {
                    Storage::delete($data->photo_stnk);
                }

                $photo_stnk = $request->file('photo_stnk')->store('public/transport');
            } else {
                $photo_stnk = $data->photo_stnk;
            }

            $query = Transport::where('id', $id)->update([
                'no_plate'   => $request->no_plate,
                'brand'      => $request->brand,
                'valid_kir'  => $request->valid_kir,
                'photo_stnk' => $photo_stnk,
                'valid_stnk' => $request->valid_stnk,
                'type'       => $request->type,
                'status'     => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Transport())
                    ->causedBy(session('id'))
                    ->log('Mengubah data kendaraan');

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
        $query = Transport::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Transport())
                ->causedBy(session('id'))
                ->log('Menghapus data kendaraan');

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
