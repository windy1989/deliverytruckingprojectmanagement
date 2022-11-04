<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {

    public function login()
    {
        if(session()->has('name')) {
            if(session()->has('id')) {
                return redirect()->back();
            } else {
                return redirect('lock_screen');
            }
        } else {
            return view('login');
        }
    }

    public function doLogin(Request $request)
    {
        if(!session()->has('id')) {
            $user = User::where('username', $request->user)
                ->orWhere('email', $request->user)
                ->orWhere('phone', $request->user)
                ->first();

            if($user) {
                if($user->status == 1) {
                    if(Hash::check($request->password, $user->password)) {
                        session([
                            'id'        => $user->id,
                            'role_id'   => $user->role_id,
                            'photo'     => $user->photo(),
                            'signature' => $user->signature(),
                            'name'      => $user->name,
                            'email'     => $user->email,
                            'status'    => $user->status
                        ]);

                        activity()
                            ->performedOn(new User())
                            ->causedBy($user->id)
                            ->log('Melakukan login');

                        session()->flash('success_login', 'Hai, Selamat Datang ' . $user->name);
                        return redirect('dashboard');
                    }
                }
            }

            session()->flash('fail_login', 'Akun tidak ditemukan.');
            return redirect('/');
        } else {
            return redirect()->back();
        }
    }

    public function activityLog(Request $request)
    {
        $activity = ActivityLog::selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_at')
            ->where('causer_id', session('id'))
            ->where(function($query) use ($request) {
                if($request->start_date && $request->finish_date) {
                    $query->whereBetween('created_at', [$request->start_date, $request->finish_date]);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }
            })
            ->distinct(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'))
            ->paginate(2);

        $data = [
            'title'       => 'Digitrans - Aktivitas',
            'activity'    => $activity,
            'start_date'  => $request->start_date,
            'finish_date' => $request->finish_date,
            'content'     => 'activity_log'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function profile(Request $request)
    {
        $id = session('id');
        if($request->has('_token')) {
            $data       = User::find($id);
            $validation = Validator::make($request->all(), [
                'photo'    => 'max:2048|mimes:jpeg,jpg,png',
                'name'     => 'required',
                'username' => ['required', Rule::unique('users', 'username')->ignore($id)],
                'email'    => ['required', Rule::unique('users', 'email')->ignore($id), 'email'],
                'phone'    => ['required', Rule::unique('users', 'phone')->ignore($id), 'min:10', 'max:12']
            ], [
                'photo.max'         => 'Foto maksimal 2MB.',
                'photo.mimes'       => 'Foto harus berformat jpeg, jpg, png.',
                'name.required'     => 'Mohon mengisi nama.',
                'username.required' => 'Mohon mengisi username.',
                'username.unique'   => 'Username telah dipakai.',
                'email.required'    => 'Mohon mengisi email.',
                'email.unique'      => 'Email telah dipakai.',
                'email.email'       => 'Email tidak valid.',
                'phone.required'    => 'Mohon mengisi telp.',
                'phone.unique'      => 'Telp sudah dipakai.',
                'phone.min'         => 'Telp minimal 10 karakter.',
                'phone.max'         => 'Telp maksimal 12 karakter.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withInput()->withErrors($validation);
            } else {
                if($request->has('change_password')) {
                    $validation = Validator::make($request->all(), [
                        'password'         => 'required',
                        'password_confirm' => 'required|same:password'
                    ], [
                        'password.required'         => 'Mohon mengisi password.',
                        'password_confirm.required' => 'Mohon mengisi konfirmasi password.',
                        'password_confirm.same'     => 'Password tidak cocok.'
                    ]);
                }

                if($validation->fails()) {
                    return redirect()->back()->withInput()->withErrors($validation);
                } else {
                    if($request->has('photo')) {
                        if($data->photo && asset(Storage::url($data->photo))) {
                            Storage::delete($data->photo);
                        }

                        $photo = $request->file('photo')->store('public/user');
                    } else {
                        $photo = $data->photo;
                    }

                    $query = User::where('id', $id)->update([
                        'photo'    => $photo,
                        'name'     => $request->name,
                        'username' => $request->username,
                        'password' => $request->password ? Hash::make($request->password) : $data->password,
                        'email'    => $request->email,
                        'phone'    => $request->phone,
                        'address'  => $request->address
                    ]);

                    if($query) {
                        activity()
                            ->performedOn(new User())
                            ->causedBy(session('id'))
                            ->log('Mengubah data profil');

                        session()->flash('success', 'Perubahan telah disimpan.');
                        return redirect()->back();
                    } else {
                        session()->flash('failed', 'Perubahan gagal disimpan.');
                        return redirect()->back();
                    }
                }
            }
        }

        $data = [
            'title'   => 'Digitrans - Profil',
            'user'    => User::find(session('id')),
            'content' => 'profile'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function lockScreen()
    {
        if(!session('email')) {
            return redirect('/');
        }

        session()->forget(['id', 'status']);
        return view('lock_screen');
    }

    public function logout()
    {
        activity()->performedOn(new User())->causedBy(session('id'))->log('Melakukan logout');
        session()->flush();
        session()->flash('logout', 'Anda telah keluar.');
        return redirect('/');
    }

}
