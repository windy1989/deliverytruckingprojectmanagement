<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - User',
            'role'    => Role::all(),
            'content' => 'setting.user'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'photo',
            'signature',
            'name',
            'role_id',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = User::count();

        $query_data = User::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = User::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $photo = '<a href="' . $val->photo() . '" data-lightbox="User_' . $val->id . '" data-title="' . $val->name . '"><img src="' . $val->photo() . '" class="img-thumbnail img-fluid" style="max-width:50px; max-height:50px;"></a>';

                $signature = '<a href="' . $val->signature() . '" data-lightbox="User_Signature_' . $val->id . '" data-title="' . $val->name . '"><img src="' . $val->signature() . '" class="img-thumbnail img-fluid" style="max-width:50px; max-height:50px;"></a>';

                $response['data'][] = [
                    $nomor,
                    $photo,
                    $signature,
                    $val->name,
                    $val->role->name,
                    $val->status(),
                    '
                        <button type="button" class="btn btn-secondary btn-sm" onclick="resetPassword(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Reset Password</button>
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
            'photo'            => 'max:500|mimes:jpeg,jpg,png',
            'signature'        => 'max:500|mimes:jpeg,jpg,png',
            'name'             => 'required',
            'role_id'          => 'required',
            'username'         => 'required|unique:users,username',
            'password'         => 'required',
            'password_confirm' => 'required|same:password',
            'email'            => 'required|unique:users,email|email',
            'phone'            => 'required|unique:users,phone|min:10|max:12',
            'status'           => 'required'
        ], [
            'photo.max'                 => 'Foto maksimal 500KB.',
            'photo.mimes'               => 'Foto harus berformat jpeg, jpg, png.',
            'signature.max'             => 'Tanda tangan maksimal 500KB.',
            'signature.mimes'           => 'Tanda tangan harus berformat jpeg, jpg, png.',
            'name.required'             => 'Mohon mengisi nama.',
            'role_id.required'          => 'Mohon memilih role.',
            'username.required'         => 'Mohon mengisi username.',
            'username.unique'           => 'Username telah dipakai.',
            'password.required'         => 'Mohon mengisi password.',
            'password_confirm.required' => 'Mohon mengisi konfirmasi password.',
            'password_confirm.same'     => 'Password tidak cocok.',
            'email.required'            => 'Mohon mengisi email.',
            'email.unique'              => 'Email telah dipakai.',
            'email.email'               => 'Email tidak valid.',
            'phone.required'            => 'Mohon mengisi telp.',
            'phone.unique'              => 'Telp sudah dipakai.',
            'phone.min'                 => 'Telp minimal 10 karakter.',
            'phone.max'                 => 'Telp maksimal 12 karakter.',
            'status.required'           => 'Mohon memilih status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = User::create([
                'role_id'   => $request->role_id,
                'photo'     => $request->has('photo') ? $request->file('photo')->store('public/user/photo') : null,
                'signature' => $request->has('signature') ? $request->file('signature')->store('public/user/signature') : null,
                'name'      => $request->name,
                'username'  => $request->username,
                'password'  => Hash::make($request->password),
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'status'    => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new User())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data user ' . $query->name);

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
        $data = User::find($request->id);

        return response()->json([
            'role_id'   => $data->role_id,
            'photo'     => $data->photo(),
            'signature' => $data->signature(),
            'name'      => $data->name,
            'username'  => $data->username,
            'email'     => $data->email,
            'phone'     => $data->phone,
            'address'   => $data->address,
            'status'    => $data->status
        ]);
    }

    public function update(Request $request, $id)
    {
        $data       = User::find($id);
        $validation = Validator::make($request->all(), [
            'photo'     => 'max:500|mimes:jpeg,jpg,png',
            'signature' => 'max:500|mimes:jpeg,jpg,png',
            'name'      => 'required',
            'role_id'   => 'required',
            'username'  => ['required', Rule::unique('users', 'username')->ignore($id)],
            'email'     => ['required', Rule::unique('users', 'email')->ignore($id), 'email'],
            'phone'     => ['required', Rule::unique('users', 'phone')->ignore($id), 'min:10', 'max:12'],
            'status'    => 'required'
        ], [
            'photo.max'         => 'Foto maksimal 2MB.',
            'photo.mimes'       => 'Foto harus berformat jpeg, jpg, png.',
            'signature.max'     => 'Tanda tangan maksimal 500KB.',
            'signature.mimes'   => 'Tanda tangan harus berformat jpeg, jpg, png.',
            'name.required'     => 'Mohon mengisi nama.',
            'role_id.required'  => 'Mohon memilih role.',
            'username.required' => 'Mohon mengisi username.',
            'username.unique'   => 'Username telah dipakai.',
            'email.required'    => 'Mohon mengisi email.',
            'email.unique'      => 'Email telah dipakai.',
            'email.email'       => 'Email tidak valid.',
            'phone.required'    => 'Mohon mengisi telp.',
            'phone.unique'      => 'Telp sudah dipakai.',
            'phone.min'         => 'Telp minimal 10 karakter.',
            'phone.max'         => 'Telp maksimal 12 karakter.',
            'status.required'   => 'Mohon memilih status.'
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

                $photo = $request->file('photo')->store('public/user/photo');
            } else {
                $photo = $data->photo;
            }

            if($request->has('signature')) {
                if(Storage::exists($data->signature)) {
                    Storage::delete($data->signature);
                }

                $signature = $request->file('signature')->store('public/user/signature');
            } else {
                $signature = $data->signature;
            }

            $query = User::where('id', $id)->update([
                'role_id'   => $request->role_id,
                'photo'     => $photo,
                'signature' => $signature,
                'name'      => $request->name,
                'username'  => $request->username,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'status'    => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new User())
                    ->causedBy(session('id'))
                    ->log('Mengubah data user');

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
        $query = User::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new User())
                ->causedBy(session('id'))
                ->log('Menghapus data user');

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

    public function resetPassword(Request $request)
    {
        $query = User::where('id', $request->id)->update(['password' => Hash::make('digitrans')]);
        if($query) {
            activity()
                ->performedOn(new User())
                ->causedBy(session('id'))
                ->log('Mereset password data user');

            $response = [
                'status'  => 200,
                'message' => 'Password telah direset.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Password gagal direset.'
            ];
        }

        return response()->json($response);
    }

}
