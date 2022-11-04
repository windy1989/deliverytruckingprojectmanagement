<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller {

    public function index(Request $request)
    {
        $result = [];
        $query  = Customer::where(function($query) use ($request) {
                if($request->id) {
                    $query->where('id', $request->id);
                }

                if($request->search) {
                    $query->where('name', 'like', "%$request->search%")
                        ->orWhere('code', 'like', "%$request->search%")
                        ->orWhere('pic', 'like', "%$request->search%");
                }
            })
            ->where('status', 1)
            ->latest()
            ->get();

        if($query) {
            if($query->count() > 0) {
                foreach($query as $q) {
                    $result[] = [
                        'id'         => $q->id,
                        'city'       => $q->city->name,
                        'code'       => $q->code,
                        'name'       => $q->name,
                        'npwp'       => $q->npwp,
                        'phone'      => $q->phone,
                        'fax'        => $q->fax,
                        'website'    => $q->website,
                        'no_bill'    => $q->no_bill,
                        'bank'       => $q->bank,
                        'address'    => $q->address,
                        'pic'        => $q->pic,
                        'status'     => $q->status(),
                        'created_at' => $q->created_at,
                        'updated_at' => $q->updated_at
                    ];
                }

                $response = [
                    'status'  => 200,
                    'message' => 'Data found',
                    'result'  => $result
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

}
