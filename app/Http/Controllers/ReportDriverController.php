<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ReportDriverController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Laporan Sopir',
            'content' => 'report.driver'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'photo',
            'name',
            'city_id',
            'vendor_id',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Driver::count();

        $query_data = Driver::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhereHas('city', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Driver::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhereHas('city', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $photo = '<a href="' . $val->photo() . '" data-lightbox="Driver_' . $val->id . '" data-title="' . $val->name . '"><img src="' . $val->photo() . '" class="img-thumbnail img-fluid" style="max-width:50px; max-height:50px;"></a>';

                $response['data'][] = [
                    $nomor,
                    $photo,
                    $val->name,
                    '<a href="' . url('location/city') . '" class="text-primary">' . $val->city->name . '</a>',
                    '<a href="' . url('master_data/vendor') . '" class="text-primary">' . $val->vendor->name . '</a>',
                    $val->status(),
                    '
                        <button type="button" class="btn btn-info btn-sm" onclick="show(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Detail</button>
                    '
                ];
                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $data = Driver::find($request->id);

        return response()->json([
            'city'                  => $data->city->name,
            'vendor'                => $data->vendor->name,
            'photo'                 => $data->photo(),
            'name'                  => $data->name,
            'photo_identity_card'   => $data->photoIdentityCard(),
            'no_identity_card'      => $data->no_identity_card,
            'photo_driving_licence' => $data->photoDrivingLicence(),
            'no_driving_licence'    => $data->no_driving_licence,
            'type_driving_licence'  => $data->type_driving_licence,
            'valid_driving_licence' => $data->valid_driving_licence,
            'address'               => $data->address,
            'status'                => $data->status()
        ]);
    }

}
