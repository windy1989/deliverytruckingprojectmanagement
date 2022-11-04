<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ReportTransportController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Laporan Kendaraan',
            'content' => 'report.transport'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'photo_stnk',
            'no_plate',
            'brand',
            'valid_kir',
            'valid_stnk',
            'type',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Transport::count();

        $query_data = Transport::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('no_plate', 'like', "%$search%")
                            ->orWhere('brand', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Transport::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('no_plate', 'like', "%$search%")
                            ->orWhere('brand', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $photo_stnk = '<a href="' . $val->photoStnk() . '" data-lightbox="Transport_' . $val->id . '" data-title="' . $val->name . '"><img src="' . $val->photoStnk() . '" class="img img-responsive" style="max-width:50px; max-height:50px;"></a>';

                $response['data'][] = [
                    $nomor,
                    $photo_stnk,
                    $val->no_plate,
                    $val->brand,
                    $val->valid_kir,
                    $val->valid_stnk,
                    $val->type,
                    $val->status
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

}
