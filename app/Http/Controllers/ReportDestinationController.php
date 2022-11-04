<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportDestinationController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Laporan Harga Per Tujuan',
            'content' => 'report.destination'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'vendor_id',
            'label',
            'city_origin',
            'city_destination',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Destination::count();

        $query_data = Destination::where(function ($query) use ($search, $request) {
                if($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('label', 'like', "%$search%")
                            ->orWhereHas('vendor', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Destination::where(function ($query) use ($search, $request) {
                if($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('label', 'like', "%$search%")
                            ->orWhereHas('vendor', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach ($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    '<a href="' . url('master_data/vendor') . '" class="text-primary">' . $val->vendor->name . '</a>',
                    $val->label,
                    '<a href="' . url('location/city') . '" class="text-primary">' . $val->cityOrigin->name . '</a>',
                    '<a href="' . url('location/city') . '" class="text-primary">' . $val->cityDestination->name . '</a>',
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
        $data              = Destination::find($request->id);
        $destination_price = [];

        foreach($data->destinationPrice as $d) {
            $destination_price[] = [
                'unit'           => $d->unit->name,
                'date'           => $d->date,
                'price_vendor'   => 'Rp.' . number_format($d->price_vendor, 2, ',', '.'),
                'price_customer' => 'Rp.' . number_format($d->price_customer, 2, ',', '.'),
            ];
        }

        return response()->json([
            'vendor'            => $data->vendor->name,
            'label'             => $data->label,
            'city_origin'       => $data->cityOrigin->name,
            'city_destination'  => $data->cityDestination,
            'destination_price' => $destination_price
        ]);
    }

}
