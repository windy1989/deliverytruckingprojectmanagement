<?php

namespace App\Http\Controllers\Api;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorController extends Controller {

    public function index(Request $request)
    {
        $result   = [];
        $query    = Vendor::where(function($query) use ($request) {
                if($request->id) {
                    $query->where('id', $request->id);
                }

                if($request->search) {
                    $query->where('name', 'like', "%$request->search%")
                        ->orWhere('code', 'like', "%$request->search%");
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
                        'code'       => $q->code,
                        'name'       => $q->name,
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
