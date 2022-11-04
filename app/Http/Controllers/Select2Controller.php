<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Load;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Province;
use Illuminate\Http\Request;

class Select2Controller extends Controller {

    public function vendor(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Vendor::select('id', 'name')
            ->where('name', 'like', "%{$search}%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function load(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Load::select('id', 'name')
            ->where('name', 'like', "%{$search}%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function order(Request $request, $status = null)
    {
        $response = [];
        $search   = $request->search;
        $data     = Order::select('id', 'code')
            ->where('code', 'like', "%{$search}%")
            ->where(function($query) use ($request, $status) {
                if($request->status) {
                    $query->where('status', $status);
                }
            })
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->code
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function province(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Province::select('id', 'name')
            ->where('name', 'like', "%{$search}%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function city(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = City::select('id', 'name')
            ->where(function($query) use ($request) {
                if($request->province_id) {
                    $query->where('province_id', $request->province_id);
                }
            })
            ->where('name', 'like', "%{$search}%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }

}
