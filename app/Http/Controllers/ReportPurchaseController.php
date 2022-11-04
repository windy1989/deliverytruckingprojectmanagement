<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\LetterWay;
use App\Models\Repayment;
use App\Models\ClaimDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportPurchaseController extends Controller {

    public function index()
    {
        $data = [
            'title'    => 'Digitrans - Laporan Pembelian',
            'vendor'   => Vendor::select('id', 'name')->where('status', 1)->get(),
            'content'  => 'report.purchase'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'vendor_id',
            'status',
            'total',
            'paid_off',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Repayment::count();

        $query_data = Repayment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $new_start_date = date('m-01-Y', strtotime($request->start_date));
                    $new_end_date = date('m-t-Y', strtotime($request->finish_date));
                    $query->whereBetween('created_at', [$new_start_date, $new_end_date]);
                } else if($request->start_date) {
                    $new_start_date = date('m-01-Y', strtotime($request->start_date));
                    $query->whereDate('created_at', $new_start_date);
                } else if($request->finish_date) {
                    $new_end_date = date('m-t-Y', strtotime($request->finish_date));
                    $query->whereDate('created_at', $new_end_date);
                }

                if($request->vendor_id) {
                    $query->where('vendor_id', $request->vendor_id);
                }

                if($request->radio == 'paid') {
                    $query->whereRaw('paid_off >= ROUND(total - claim, 2)');
                } else if($request->radio == 'no_paid') {
                    $query->whereRaw('paid_off < ROUND(total - claim, 2)');
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Repayment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $new_start_date = date('m-01-Y', strtotime($request->start_date));
                    $new_end_date = date('m-t-Y', strtotime($request->finish_date));
                    $query->whereBetween('created_at', [$new_start_date, $new_end_date]);
                } else if($request->start_date) {
                    $new_start_date = date('m-01-Y', strtotime($request->start_date));
                    $query->whereDate('created_at', $new_start_date);
                } else if($request->finish_date) {
                    $new_end_date = date('m-t-Y', strtotime($request->finish_date));
                    $query->whereDate('created_at', $new_end_date);
                }

                if($request->vendor_id) {
                    $query->where('vendor_id', $request->vendor_id);
                }

                if($request->radio) {
                    if($request->radio == 'paid') {
                        $query->whereRaw('paid_off >= ROUND(total - claim, 2)');
                    } else if($request->radio == 'no_paid') {
                        $query->whereRaw('paid_off < ROUND(total - claim, 2)');
                    }
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->vendor->name,
                    'Rp ' . number_format(($val->total - $val->claim), 2, '.', ','),
                    'Rp ' . number_format($val->paid_off,2,'.',','),
                    date('d M Y', strtotime($val->created_at)),
                    '
                        <a href="' . url('report/purchase/claim/' . $val->id) . '" class="btn btn-warning btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg> Klaim</a>
                        <a href="' . url('report/purchase/detail/' . $val->id) . '" class="btn btn-info btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Detail</a>
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

    public function claim(Request $request, $id)
    {
        $repayment = Repayment::find($id);
        $claim     = [];

        foreach($repayment->repaymentDetail as $rd) {
            $deadline        = $rd->letterWay->order->deadline;
            $order_date      = date('Y-m-d', strtotime("+$deadline day", strtotime($rd->letterWay->order->date)));
            $letter_way_date = date('Y-m-d', strtotime($rd->letterWay->updated_at));
            $ttbr_qty        = $rd->letterWay->ttbr_qty;
            $difference_late = strtotime($letter_way_date) - strtotime($order_date);
            $total_late      = $difference_late / 60 / 60 / 24;

            if($ttbr_qty > 0) {
                if($repayment->claims) {
                    $letter_way_id = null;
                    $data_claim    = ClaimDetail::where('claim_details.letter_way_id', $rd->letterWay->id)
                        ->where('claims.claimable_type', 'repayments')
                        ->leftJoin('claims', 'claims.id', '=', 'claim_details.claim_id')
                        ->first();

                    $claim_rupiah    = $data_claim ? $data_claim->rupiah : 0;
                    $claim_tolerance = $data_claim ? $data_claim->tolerance : 0;
                    $rupiah          = $data_claim ? 'Rp ' . number_format($claim_rupiah, 2, ',', '.') : 'Rp 0,00';
                    $tolerance       = $data_claim ? 'Rp ' . number_format($claim_tolerance, 2, ',', '.') : 'Rp 0,00';
                    $total           = $claim_rupiah - $claim_tolerance;
                } else {
                    $letter_way_id = '<input type="hidden" name="claim_detail_letter_way_id[]" value="' . $rd->letterWay->id . '">';
                    $total         = 0;

                    $rupiah = '
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="claim_detail_rupiah[]" class="form-control form-control-sm" step="any">
                            </div>
                        </div>
                    ';

                    $tolerance = '
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="claim_detail_tolerance[]" class="form-control form-control-sm" step="any">
                            </div>
                        </div>
                    ';
                }

                $letter_way['destroy'][] = (object)[
                    'number'        => $rd->letterWay->number,
                    'letter_way_id' => $letter_way_id,
                    'type'          => 'destroy',
                    'late'          => '<input type="hidden" name="claim_detail_late[]" value="0">',
                    'percentage'    => '<input type="hidden" name="claim_detail_percentage[]" value="0">',
                    'status'        => 'Pecah <b>' . $ttbr_qty . '</b>',
                    'rupiah'        => $rupiah,
                    'tolerance'     => $tolerance,
                    'total'         => 'Rp ' . number_format($total, 2, ',', '.')
                ];
            } else if($letter_way_date > $order_date) {
                if($repayment->claims) {
                    $letter_way_id = null;
                    $late          = null;
                    $data_claim    = ClaimDetail::where('claim_details.letter_way_id', $rd->letterWay->id)
                        ->where('claims.claimable_type', 'repayments')
                        ->leftJoin('claims', 'claims.id', '=', 'claim_details.claim_id')
                        ->first();

                    $claim_percentage = $data_claim ? $data_claim->percentage : 0;
                    $percentage       = $claim_percentage . '%';
                    $calculate_by     = $repayment->flag == 1 ? $rd->letterWay->weight : $rd->letterWay->qty;
                    $price            = $rd->letterWay->destination->destinationPrice->last();
                    $price_vendor     = $price ? $price->price_vendor : 0;
                    $total            = $total_late * ($calculate_by * $price_vendor) * ($claim_percentage / 100);
                } else {
                    $letter_way_id = '<input type="hidden" name="claim_detail_letter_way_id[]" value="' . $rd->letterWay->id . '">';
                    $late          = '<input type="hidden" name="claim_detail_late[]" value="' . $total_late . '">';
                    $total         = 0;

                    $percentage = '
                        <div class="form-group">
                            <div class="input-group">
                                <input type="number" name="claim_detail_percentage[]" class="form-control form-control-sm" step="any">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    ';
                }

                $letter_way['late'][] = (object)[
                    'number'        => $rd->letterWay->number,
                    'letter_way_id' => $letter_way_id,
                    'type'          => 'late',
                    'status'        => 'Terlambat <b>' . $total_late . '</b> Hari',
                    'late'          => $late,
                    'percentage'    => $percentage,
                    'rupiah'        => '<input type="hidden" name="claim_detail_rupiah[]" value="0">',
                    'tolerance'     => '<input type="hidden" name="claim_detail_tolerance[]" value="0">',
                    'total'         => 'Rp ' . number_format($total, 2, ',', '.')
                ];
            } else {
                $letter_way['fine'][] = (object)[
                    'number' => $rd->letterWay->number
                ];
            }
        }

        $data = [
            'title'      => 'Detail Klaim - ' . $repayment->code,
            'repayment'  => $repayment,
            'letter_way' => $letter_way,
            'content'    => 'report.purchase_claim'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function detail($id)
    {
        $repayment = Repayment::find($id);
        $data      = [
            'title'     => 'Digitrans - ' . $repayment->code,
            'repayment' => $repayment,
            'vendor'    => Vendor::select('id', 'name')->where('status', 1)->get(),
            'content'   => 'report.purchase_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
