<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProtectLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $id   = session('id');
        $user = User::find($id);

        if(session()->has('id')) {
            if($user) {
                if($user->status == 1) {
                    session([
                        'id'        => $user->id,
                        'role_id'   => $user->role_id,
                        'photo'     => $user->photo ? asset(Storage::url($user->photo)) : asset('website/user.png'),
                        'signature' => $user->signature ? asset(Storage::url($user->signature)) : null,
                        'name'      => $user->name,
                        'email'     => $user->email,
                        'status'    => $user->status
                    ]);

                    return $next($request);
                }
            }
        }

        return redirect('/');
    }
}
