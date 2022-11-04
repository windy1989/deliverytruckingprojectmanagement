<?php

namespace App\Http\Controllers\Api;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class WarehouseController extends Controller {
    
    public function index(Request $request)
    {
        $result   = [];
        $per_page = 10;
        $query    = Warehouse::where(function($query) use ($request) {
                if($request->id) {
                    $query->where('id', $request->id);
                }
            })
            ->latest()
            ->get();

        if($query) {
            if($query->count() > 0) {
                foreach($query as $q) {
                    $result[] = [
                        'id'         => $q->id,
                        'code'       => $q->code,
                        'name'       => $q->name,
                        'address'    => $q->address,
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
