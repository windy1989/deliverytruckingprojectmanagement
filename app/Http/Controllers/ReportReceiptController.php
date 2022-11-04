<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Receipt;
use App\Models\Customer;
use App\Models\LetterWay;
use App\Models\ClaimDetail;
use Illuminate\Http\Request;
use App\Models\ReceiptDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReportReceiptController extends Controller {

    public function index()
    {
        $data = [
            'title'    => 'Digitrans - Laporan Kwitansi',
            'customer' => Customer::select('id', 'name')->where('status', 1)->get(),
            'content'  => 'report.receipt'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'customer_id',
            'deleted_at',
            'total',
            'paid_off',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Receipt::withTrashed()
            ->count();

        $query_data = Receipt::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('customer', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }

                if($request->customer_id) {
                    $query->where('customer_id', $request->customer_id);
                }

                if($request->status) {
                    if($request->status == 1) {
                        $query->whereNull('deleted_at');
                    } else {
                        $query->whereNotNull('deleted_at');
                    }
                }
            })
            ->withTrashed()
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Receipt::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('customer', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }

                if($request->customer_id) {
                    $query->where('customer_id', $request->customer_id);
                }

                if($request->status) {
                    if($request->status == 1) {
                        $query->whereNull('deleted_at');
                    } else {
                        $query->whereNotNull('deleted_at');
                    }
                }
            })
            ->withTrashed()
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                if($val->deleted_at) {
                    $status = '<span class="font-weight-bold text-danger">Dibatalkan</span>';
                } else {
                    $status = '<span class="font-weight-bold text-success">Aktif</span>';
                }

                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->customer->name,
                    $status,
                    'Rp ' . number_format(($val->total - $val->claim), 2, ',', '.'),
                    'Rp ' . number_format($val->paid_off, 2, ',', '.'),
                    date('d M Y', strtotime($val->created_at)),
                    '
                        <a href="' . url('report/receipt/reception/' . $val->id) . '" class="btn btn-success btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> Tanda Terima</a>
                        <a href="' . url('report/receipt/claim/' . $val->id) . '" class="btn btn-warning btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg> Klaim</a>
                        <a href="' . url('report/receipt/detail/' . $val->id) . '" class="btn btn-info btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Detail</a>
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
        $receipt = Receipt::find($id);
        $claim   = [];

        foreach($receipt->receiptDetail as $rd) {
            foreach($rd->invoice->invoiceDetail as $id) {
                $deadline        = $id->letterWay->order->deadline;
                $order_date      = date('Y-m-d', strtotime("+$deadline day", strtotime($id->letterWay->order->date)));
                $letter_way_date = date('Y-m-d', strtotime($id->letterWay->updated_at));
                $ttbr_qty        = $id->letterWay->ttbr_qty;
                $difference_late = strtotime($letter_way_date) - strtotime($order_date);
                $total_late      = $difference_late / 60 / 60 / 24;

                if($ttbr_qty > 0) {
                    if($receipt->claims) {
                        $letter_way_id = null;
                        $data_claim    = ClaimDetail::where('claim_details.letter_way_id', $id->letterWay->id)
                            ->where('claims.claimable_type', 'receipts')
                            ->leftJoin('claims', 'claims.id', '=', 'claim_details.claim_id')
                            ->first();

                        $claim_rupiah    = $data_claim ? $data_claim->rupiah : 0;
                        $claim_tolerance = $data_claim ? $data_claim->tolerance : 0;
                        $rupiah          = $data_claim ? 'Rp ' . number_format($claim_rupiah, 2, ',', '.') : 'Rp 0,00';
                        $tolerance       = $data_claim ? 'Rp ' . number_format($claim_tolerance, 2, ',', '.') : 'Rp 0,00';
                        $total           = $claim_rupiah - $claim_tolerance;
                    } else {
                        $letter_way_id = '<input type="hidden" name="claim_detail_letter_way_id[]" value="' . $id->letterWay->id . '">';
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
                        'number'        => $id->letterWay->number,
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
                    if($receipt->claims) {
                        $letter_way_id = null;
                        $late          = null;
                        $data_claim    = ClaimDetail::where('claim_details.letter_way_id', $id->letterWay->id)
                            ->where('claims.claimable_type', 'receipts')
                            ->leftJoin('claims', 'claims.id', '=', 'claim_details.claim_id')
                            ->first();

                        $claim_percentage = $data_claim ? $data_claim->percentage : 0;
                        $percentage       = $claim_percentage . '%';
                        $calculate_by     = $receipt->flag == 1 ? $id->letterWay->weight : $id->letterWay->qty;
                        $price            = $id->letterWay->destination->destinationPrice->last();
                        $price_customer   = $price ? $price->price_customer : 0;
                        $total            = $total_late * ($calculate_by * $price_customer) * ($claim_percentage / 100);
                    } else {
                        $letter_way_id = '<input type="hidden" name="claim_detail_letter_way_id[]" value="' . $id->letterWay->id . '">';
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
                        'number'        => $id->letterWay->number,
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
                        'number' => $id->letterWay->number
                    ];
                }
            }
        }

        $data = [
            'title'      => 'Detail Klaim - ' . $receipt->code,
            'receipt'    => $receipt,
            'letter_way' => $letter_way,
            'content'    => 'report.receipt_claim'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function detail($id)
    {
        $receipt = Receipt::find($id);
        $data    = [
            'title'   => 'Digitrans - ' . $receipt->code,
            'receipt' => $receipt,
            'content' => 'report.receipt_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function reception($id)
    {
        $receipt = Receipt::find($id);
        $data    = [
            'title'   => 'Digitrans - Tanda Terima Kwitansi',
            'receipt' => $receipt,
            'content' => 'report.receipt_reception'
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
