<?php

namespace App\Http\Controllers\Api;

use App\Models\Destination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DestinationController extends Controller {
    
    public function index(Request $request)
    {
        $result = [];
        $query  = Destination::where(function($query) use ($request) {
                if($request->id) {
                    $query->where('id', $request->id);
                }

                if($request->vendor_id) {
                    $query->where('vendor_id', $request->vendor_id);
                }
            })
            ->latest()
            ->get();

        if($query) {
            if($query->count() > 0) {
                foreach($query as $q) {
                    $result[] = [
                        'id'               => $q->id,
                        'city_origin'      => $q->cityOrigin,
                        'city_destination' => $q->cityDestination,
                        'created_at'       => $q->created_at,
                        'updated_at'       => $q->updated_at
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
