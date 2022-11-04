<?php

namespace App\Http\Controllers\Api;

use App\Models\Transport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TransportController extends Controller {

    public function index(Request $request)
    {
        $result   = [];
        $per_page = 10;
        $query    = Transport::where(function($query) use ($request) {
                if($request->id) {
                    $query->where('id', $request->id);
                }
            })
            ->latest()
            ->get();

        if($query) {
            if($query->count() > 0) {
                foreach($query as $q) {
                    $default  = asset('website/empty.png');
                    $result[] = [
                        'id'         => $q->id,
                        'no_plate'   => $q->no_plate,
                        'brand'      => $q->brand,
                        'valid_kir'  => $q->valid_kir,
                        'photo_stnk' => $q->photoStnk(),
                        'valid_stnk' => $q->valid_stnk,
                        'type'       => $q->type,
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
