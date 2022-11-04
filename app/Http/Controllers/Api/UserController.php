<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    public function login(Request $request) 
    {
        $user     = $request->user;
        $password = $request->password;
        $query    = User::where('username', $request->user)
            ->orWhere('phone', $request->user)
            ->orWhere('email', $request->user)
            ->first();

        if($query) {
            if(Hash::check($password, $query->password)) {
                $response = [
                    'status'  => 200,
                    'message' => 'User found',
                    'result'  => $query
                ];    
            }
        } else {
            $response = [
                'status'  => 500,
                'message' => 'User not found'
            ];
        }

        return response()->json($response, $response['status']);
    }

}
