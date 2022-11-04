<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Sopir',
            'content' => 'master_data.driver'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'photo',
            'name',
            'city_id',
            'vendor_id',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Driver::count();

        $query_data = Driver::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhereHas('city', function($query) use ($search) {
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
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Driver::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhereHas('city', function($query) use ($search) {
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
                $photo = '<a href="' . $val->photo() . '" data-lightbox="Driver_' . $val->id . '" data-title="' . $val->name . '"><img src="' . $val->photo() . '" class="img-thumbnail img-fluid" style="max-width:50px; max-height:50px;"></a>';

                $response['data'][] = [
                    $nomor,
                    $photo,
                    $val->name,
                    '<a href="' . url('location/city') . '" class="text-primary">' . $val->city->name . '</a>',
                    '<a href="' . url('master_data/vendor') . '" class="text-primary">' . $val->vendor->name . '</a>',
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
            'city_id'               => 'required',
            'vendor_id'             => 'required',
            'photo'                 => 'max:2048|mimes:jpeg,jpg,png',
            'name'                  => 'required',
            'photo_identity_card'   => 'max:2048|mimes:jpeg,jpg,png',
            'no_identity_card'      => 'required|min:16|max:16',
            'photo_driving_licence' => 'max:2048|mimes:jpeg,jpg,png',
            'no_driving_licence'    => 'required|min:13|max:13',
            'type_driving_licence'  => 'required',
            'valid_driving_licence' => 'required',
            'status'                => 'required'
        ], [
            'city_id.required'               => 'Mohon memilih kota.',
            'vendor_id.required'             => 'Mohon memilih vendor.',
            'photo.max'                      => 'Foto maksimal 2MB.',
            'photo.mimes'                    => 'Foto harus berformat jpeg, jpg, png.',
            'name.required'                  => 'Mohon mengisi nama.',
            'photo_identity_card.max'        => 'Foto KTP maksimal 2MB.',
            'photo_identity_card.mimes'      => 'Foto KTP harus berformat jpeg, jpg, png.',
            'no_identity_card.required'      => 'Mohon mengisi nomor KTP.',
            'no_identity_card.min'           => 'Nomor KTP minimal 16 karakter',
            'no_identity_card.max'           => 'Nomor KTP maksimal 16 karakter.',
            'photo_driving_licence.max'      => 'Foto SIM maksimal 2MB.',
            'photo_driving_licence.mimes'    => 'Foto SIM harus berformat jpeg, jpg, png.',
            'no_driving_licence.required'    => 'Mohon mengisi nomor SIM.',
            'no_driving_licence.min'         => 'Nomor SIM minimal 13 karakter.',
            'no_driving_licence.max'         => 'Nomor SIM maksimal 13 karakter.',
            'type_driving_licence.required'  => 'Mohon memilih jenis SIM.',
            'valid_driving_licence.required' => 'Mohon mengisi masa berlaku SIM.',
            'status.required'                => 'Mohon memilih status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $photo = $request->file('photo') ? $request->file('photo')->store('public/driver/photo') : null;

            $photo_identity_card = $request->file('photo_identity_card') ? $request->file('photo_identity_card')->store('public/driver/identity_card') : null;

            $photo_driving_licence = $request->file('photo_driving_licence') ? $request->file('photo_driving_licence')->store('public/driver/driving_licence') : null;

            $query = Driver::create([
                'city_id'               => $request->city_id,
                'vendor_id'             => $request->vendor_id,
                'photo'                 => $photo,
                'name'                  => $request->name,
                'photo_identity_card'   => $photo_identity_card,
                'no_identity_card'      => $request->no_identity_card,
                'photo_driving_licence' => $photo_driving_licence,
                'no_driving_licence'    => $request->no_driving_licence,
                'type_driving_licence'  => $request->type_driving_licence,
                'valid_driving_licence' => $request->valid_driving_licence,
                'address'               => $request->address,
                'status'                => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Driver())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data sopir ' . $query->name);

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
        $data = Driver::find($request->id);

        return response()->json([
            'city_id'               => $data->city_id,
            'city_name'             => $data->city->name,
            'vendor_id'             => $data->vendor_id,
            'vendor_name'           => $data->vendor->name,
            'photo'                 => $data->photo(),
            'name'                  => $data->name,
            'photo_identity_card'   => $data->photoIdentityCard(),
            'no_identity_card'      => $data->no_identity_card,
            'photo_driving_licence' => $data->photoDrivingLicence(),
            'no_driving_licence'    => $data->no_driving_licence,
            'type_driving_licence'  => $data->type_driving_licence,
            'valid_driving_licence' => $data->valid_driving_licence,
            'address'               => $data->address,
            'status'                => $data->status
        ]);
    }

    public function update(Request $request, $id)
    {
        $data       = Driver::find($id);
        $validation = Validator::make($request->all(), [
            'city_id'               => 'required',
            'vendor_id'             => 'required',
            'photo'                 => 'max:2048|mimes:jpeg,jpg,png',
            'name'                  => 'required',
            'photo_identity_card'   => 'max:2048|mimes:jpeg,jpg,png',
            'no_identity_card'      => 'required|min:16|max:16',
            'photo_driving_licence' => 'max:2048|mimes:jpeg,jpg,png',
            'no_driving_licence'    => 'required|min:13|max:13',
            'type_driving_licence'  => 'required',
            'valid_driving_licence' => 'required',
            'status'                => 'required'
        ], [
            'city_id.required'               => 'Mohon memilih kota.',
            'vendor_id.required'             => 'Mohon memilih vendor.',
            'photo.max'                      => 'Foto maksimal 2MB.',
            'photo.mimes'                    => 'Foto harus berformat jpeg, jpg, png.',
            'name.required'                  => 'Mohon mengisi nama.',
            'photo_identity_card.max'        => 'Foto KTP maksimal 2MB.',
            'photo_identity_card.mimes'      => 'Foto KTP harus berformat jpeg, jpg, png.',
            'no_identity_card.required'      => 'Mohon mengisi nomor KTP.',
            'no_identity_card.min'           => 'Nomor KTP minimal 16 karakter',
            'no_identity_card.max'           => 'Nomor KTP maksimal 16 karakter.',
            'photo_driving_licence.max'      => 'Foto SIM maksimal 2MB.',
            'photo_driving_licence.mimes'    => 'Foto SIM harus berformat jpeg, jpg, png.',
            'no_driving_licence.required'    => 'Mohon mengisi nomor SIM.',
            'no_driving_licence.min'         => 'Nomor SIM minimal 13 karakter.',
            'no_driving_licence.max'         => 'Nomor SIM maksimal 13 karakter.',
            'type_driving_licence.required'  => 'Mohon memilih jenis SIM.',
            'valid_driving_licence.required' => 'Mohon mengisi masa berlaku SIM.',
            'status.required'                => 'Mohon memilih status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->has('photo')) {
                if(Storage::exists($data->photo)) {
                    Storage::delete($data->photo);
                }

                $photo = $request->file('photo')->store('public/driver/photo');
            } else {
                $photo = $data->photo;
            }

            if($request->has('photo_identity_card')) {
                if(Storage::exists($data->photo_identity_card)) {
                    Storage::delete($data->photo_identity_card);
                }

                $photo_identity_card = $request->file('photo_identity_card')->store('public/driver/identity_card');
            } else {
                $photo_identity_card = $data->photo_identity_card;
            }

            if($request->has('photo_driving_licence')) {
                if(Storage::exists($data->photo_driving_licence)) {
                    Storage::delete($data->photo_driving_licence);
                }

                $photo_driving_licence = $request->file('photo_driving_licence')->store('public/driver/driving_licence');
            } else {
                $photo_driving_licence = $data->photo_driving_licence;
            }

            $query = Driver::where('id', $id)->update([
                'city_id'               => $request->city_id,
                'vendor_id'             => $request->vendor_id,
                'photo'                 => $photo,
                'name'                  => $request->name,
                'photo_identity_card'   => $photo_identity_card,
                'no_identity_card'      => $request->no_identity_card,
                'photo_driving_licence' => $photo_driving_licence,
                'no_driving_licence'    => $request->no_driving_licence,
                'type_driving_licence'  => $request->type_driving_licence,
                'valid_driving_licence' => $request->valid_driving_licence,
                'address'               => $request->address,
                'status'                => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Driver())
                    ->causedBy(session('id'))
                    ->log('Mengubah data sopir');

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
        $query = Driver::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Driver())
                ->causedBy(session('id'))
                ->log('Menghapus data sopir');

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
