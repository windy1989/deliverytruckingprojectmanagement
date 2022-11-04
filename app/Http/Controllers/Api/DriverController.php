<?php

namespace App\Http\Controllers\Api;

use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller {

    public function index(Request $request)
    {
        $result = [];
        $query  = Driver::where(function($query) use ($request) {
                if($request->id) {
                    $query->where('id', $request->id);
                }

                if($request->vendor_id) {
                    $query->where('vendor_id', $request->vendor_id);
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
                        'id'                    => $q->id,
                        'city'                  => $q->city->name,
                        'vendor'                => $q->vendor->name,
                        'photo'                 => $q->photo(),
                        'name'                  => $q->name,
                        'photo_identity_card'   => $q->photoIdentityCard(),
                        'no_identity_card'      => $q->no_identity_card,
                        'photo_driving_licence' => $q->photoDrivingLicence(),
                        'no_driving_licence'    => $q->no_driving_licence,
                        'type_driving_licence'  => $q->type_driving_licence,
                        'valid_driving_licence' => $q->valid_driving_licence,
                        'address'               => $q->address,
                        'status'                => $q->status(),
                        'created_at'            => $q->created_at,
                        'updated_at'            => $q->updated_at
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
