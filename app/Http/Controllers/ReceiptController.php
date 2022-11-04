<?php

namespace App\Http\Controllers;

use App\Models\Claim;
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
use App\Models\Photo;
use Illuminate\Support\Facades\Validator;

class ReceiptController extends Controller {

    public function index()
    {
        $data = [
            'title'    => 'Digitrans - Kwitansi',
            'customer' => Customer::where('status', 1)->get(),
            'content'  => 'sales.receipt'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'customer_id',
            'paidable',
            'status',
            'due_date',
            'total',
            'paid_off',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Receipt::whereRaw('paid_off < ROUND(total - claim, 2)')
            ->count();

        $query_data = Receipt::whereRaw('paid_off < ROUND(total - claim, 2)')
            ->where(function($query) use ($search, $request) {
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

                if($request->customer_id) {
                    $query->where('customer_id', $request->customer_id);
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Receipt::whereRaw('paid_off < ROUND(total - claim, 2)')
            ->where(function($query) use ($search, $request) {
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

                if($request->customer_id) {
                    $query->where('customer_id', $request->customer_id);
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $rest          = ($val->total - $val->claim) - $val->paid_off;
                $total_receipt = $val->total - $val->claim;
                $current_date  = strtotime(date('Y-m-d'));
                $due_date      = strtotime($val->due_date);

                if($current_date == $due_date) {
                    $status = '<span class="text-info font-weight-bold">Jatuh tempo hari ini</span>';
                } else if($current_date > $due_date) {
                    $status = '<span class="text-danger font-weight-bold">Lewat jatuh tempo</span>';
                } else {
                    $day    = ($due_date - $current_date) / 60 / 60 / 24;
                    $status = '<span class="text-success font-weight-bold">Jatuh tempo ' . $day . ' hari lagi</span>';
                }

                if($val->paid_off > 0 && ($val->paid_off < $total_receipt)) {
                    $paidable = '<span class="text-warning font-weight-bold">Belum Lunas</span>';
                    $btn_paid = '<button type="button" class="btn btn-success btn-sm" onclick="paid(' . $val->id . ', ' . $val->paid_off . ', `' . $val->code . '`, ' . $total_receipt . ', ' . $rest . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Bayar</button>';
                } else {
                    $paidable = '<span class="text-danger font-weight-bold">Belum Dibayar</span>';
                    $btn_paid = '<button type="button" class="btn btn-success btn-sm" onclick="paid(' . $val->id . ', ' . $val->paid_off . ', `' . $val->code . '`, ' . $total_receipt . ', ' . $rest . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Bayar</button>';
                }

                if($val->paid_off > 0) {
                    $destroy = null;
                } else {
                    $destroy = '<button type="button" class="btn btn-danger btn-sm" onclick="destroy(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus</button>';
                }

                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->customer->name,
                    $paidable,
                    $status,
                    date('F d, Y', strtotime($val->due_date)),
                    'Rp ' . number_format($total_receipt, 2, ',', '.'),
                    'Rp ' . number_format($val->paid_off, 2, ',', '.'),
                    date('d M Y', strtotime($val->created_at)),
                    '
                        ' . $btn_paid . '
                        <a href="' . url('sales/receipt/claim/' . $val->id) . '" class="btn btn-warning btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg> Klaim</a>
                        <a href="' . url('sales/receipt/detail/' . $val->id) . '" class="btn btn-secondary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> Detail</a>
                    '
                    . $destroy
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

    public function grandtotal(Request $request)
    {
        $receipt = Receipt::where(function($query) use ($request) {
                if($request->customer_id) {
                    $query->where('customer_id', $request->customer_id);
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }
            })
            ->sum('total');

        return response()->json('Rp ' . number_format($receipt, 2, ',', '.'));
    }

    public function getInvoice(Request $request)
    {
        $response = [];
        $data     = Invoice::where('customer_id', $request->customer_id)
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('receipt_details')
                    ->whereColumn('receipt_details.invoice_id', 'invoices.id');
            })
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'    => $d->id,
                'code'  => $d->code,
                'date'  => date('d M Y', strtotime($d->created_at)),
                'total' => 'Rp ' . number_format($d->grandtotal, 2, ',', '.')
            ];
        }

        return response()->json($response);
    }

    public function totalReceipt(Request $request)
    {
        $total    = 0;
        $other    = $request->has('other') ? (double)str_replace(',', '', $request->other) : 0;
        $paid_off = $request->has('paid_off') ? (double)str_replace(',', '', $request->paid_off) : 0;

        if($request->has('receipt_detail')) {
            foreach($request->receipt_detail as $rd) {
                $invoice  = Invoice::find($rd);
                $total   += $invoice->grandtotal;
            }
        }

        if($total > 0) {
            $subtotal   = $total + $other;
            $grandtotal = ($total + $other) - $paid_off;
        } else {
            $subtotal   = 0;
            $grandtotal =  0;
        }

        return response()->json([
            'total'  => $subtotal,
            'total1' => number_format($subtotal, 2, '.', ','),
            'bill'   => $grandtotal,
            'bill1'  => number_format($grandtotal, 2, '.', ','),
        ]);
    }

    public function create(Request $request)
    {
        if($request->has('_token')) {
            $validation = Validator::make($request->all(), [
                'customer_id'    => 'required',
                'due_date'       => 'required',
                'receipt_detail' => 'required'
            ], [
                'customer_id.required'    => 'Mohon memilih customer.',
                'due_date.required'       => 'Jatuh tempo tidak boleh kosong.',
                'receipt_detail.required' => 'Mohon memilih salah satu invoice.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $query = Receipt::create([
                    'user_id'     => session('id'),
                    'customer_id' => $request->customer_id,
                    'code'        => Receipt::generateCode(),
                    'other'       => str_replace(',', '', $request->other),
                    'paid_off'    => str_replace(',', '', $request->paid_off),
                    'due_date'    => $request->due_date,
                    'total'       => str_replace(',', '', $request->total)
                ]);

                if($query) {
                    foreach($request->receipt_detail as $rd) {
                        $invoice = Invoice::find($rd);
                        $total   = $invoice->grandtotal;

                        ReceiptDetail::create([
                            'receipt_id' => $query->id,
                            'invoice_id' => $rd
                        ]);
                    }

                    Journal::create([
                        'coa_debit'   => 3001,
                        'coa_credit'  => 2001,
                        'nominal'     => $query->total,
                        'description' => $query->code
                    ]);

                    if($query->paid_off > 0) {
                        Journal::create([
                            'coa_debit'   => 2001,
                            'coa_credit'  => 3001,
                            'nominal'     => $query->paid_off,
                            'description' => $query->code
                        ]);
                    }

                    activity()
                        ->performedOn(new Receipt())
                        ->causedBy(session('id'))
                        ->withProperties($query)
                        ->log('Menambah data kwitansi ' . $query->code);

                    session()->flash('success', 'Kwitansi berhasil di buat.');
                    return redirect()->back();
                } else {
                    session()->flash('failed', 'Kwitansi gagal di buat.');
                    return redirect()->back();
                }
            }
        } else {
            $data = [
                'title'    => 'Digitrans - Buat Kwitansi',
                'customer' => Customer::select('id', 'name')->where('status', 1)->get(),
                'content'  => 'sales.receipt_create'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function claim(Request $request, $id)
    {
        $receipt    = Receipt::find($id);
        $letter_way = [];

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

        if($request->has('_token')) {
            $query = Claim::create([
                'claimable_type' => 'receipts',
                'claimable_id'   => $receipt->id,
                'date'           => date('Y-m-d'),
                'description'    => $request->description,
                'flag'           => $request->flag
            ]);

            if($query) {
                $total_claim = 0;
                if($request->claim_detail_letter_way_id) {
                    foreach($request->claim_detail_letter_way_id as $key => $cdlwi) {
                        $letter_way       = LetterWay::find($cdlwi);
                        $claim_rupiah     = (double)str_replace(',', '', $request->claim_detail_rupiah[$key]);
                        $claim_tolerance  = (double)str_replace(',', '', $request->claim_detail_tolerance[$key]);
                        $claim_percentage = (double)$request->claim_detail_percentage[$key];

                        if($request->claim_type[$key] == 'destroy') {
                            $total_claim += $claim_rupiah - $claim_tolerance;
                        } else {
                            $claim_late     = $request->claim_detail_late[$key];
                            $price          = $letter_way->destination->destinationPrice->last();
                            $price_customer = $price ? $price->price_customer : 0;
                            $calculate_by   = $request->flag == 1 ? $letter_way->weight : $letter_way->qty;
                            $total_claim   += $total_late * ($calculate_by * $price_customer) * ($claim_percentage / 100);
                        }

                        ClaimDetail::create([
                            'claim_id'      => $query->id,
                            'letter_way_id' => $cdlwi,
                            'percentage'    => $claim_percentage,
                            'rupiah'        => $claim_rupiah,
                            'tolerance'     => $claim_tolerance
                        ]);
                    }
                }

                $receipt->update(['claim' => $total_claim]);
                activity()
                    ->performedOn(new Claim())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data klaim kwitansi');

                session()->flash('success', 'Klaim kwitansi berhasil dibuat.');
                return redirect()->back();
            } else {
                session()->flash('failed', 'Klaim kwitansi gagal dibuat.');
                return redirect()->back();
            }
        } else {
            $data = [
                'title'      => 'Klaim - ' . $receipt->code,
                'receipt'    => $receipt,
                'letter_way' => $letter_way,
                'content'    => 'sales.receipt_claim'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function paid(Request $request)
    {
        $receipt  = Receipt::find($request->id);
        $paid_off = $receipt->paid_off + (double)str_replace(',', '', $request->paid_off);
        $query    = Receipt::where('id', $request->id)->update(['paid_off' => $paid_off]);
        if($query) {
            Journal::create([
                'coa_debit'   => 2001,
                'coa_credit'  => 3001,
                'nominal'     => str_replace(',', '', $request->paid_off),
                'description' => $receipt->code
            ]);

            activity()
                ->performedOn(new Receipt())
                ->causedBy(session('id'))
                ->log('Melakukan pembayaran tagihan kwitansi ' . $receipt->code);

            $response = [
                'status'  => 200,
                'message' => 'Data berhasil diproses'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data gagal diproses'
            ];
        }

        return response()->json($response);
    }

    public function detail($id)
    {
        $receipt = Receipt::find($id);
        $data    = [
            'title'   => 'Digitrans - ' . $receipt->code,
            'receipt' => $receipt,
            'content' => 'sales.receipt_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function destroy(Request $request)
    {
        $query = Receipt::where('id', $request->id)->delete();
        if($query) {
            $receipt_detail = ReceiptDetail::where('receipt_id', $request->id)->get();
            foreach($receipt_detail as $rd) {
                Invoice::find($rd->invoice_id)->delete();
            }

            activity()
                ->performedOn(new Receipt())
                ->causedBy(session('id'))
                ->log('Menghapus data kwitansi');

            $response = [
                'status'  => 200,
                'message' => 'Data telah dihapus.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data gagal dihapus.'
            ];
        }

        return response()->json($response);
    }

    public function addPicture(Request $request)
    {
        $file_name =  $request->file('file')->getClientOriginalName();
        $query = Photo::create([
            'photo_type'            => $request->type,
            'photo_id'            => $request->photoable_id,
            'name'            => $request->file('file') ? $request->file('file')->getClientOriginalName() : '',
            'path'            => $request->file('file') ? $request->file('file')->storeAs('public/claim/receipt', $file_name) : '',
            'date'            => date('Y-m-d')
        ]);

        if ($query) {
            $response = [
                'success' =>  $file_name
            ];
        } else {
            $response = [
                'success' =>  'error'
            ];
        }
        return response()->json($response);
    }
    public function getPicture(Request $request)
    {

        $images = Photo::where([
            ['photo_type', $request->type],
            ['photo_id', $request->photoable_id]
        ])->get()->toArray();
        foreach ($images as $image) {
            $tableImages[] = $image['name'];
        }
        $storeFolder = public_path('storage/claim/receipt');
        $file_path = public_path('storage/claim/receipt/');
        $files = scandir($storeFolder);
        $data = [];
        foreach ($files as $file) {
            if (in_array($file, $tableImages)) {
                $obj['name'] = $file;
                $file_path = public_path('storage/claim/receipt/') . $file;
                $obj['size'] = filesize($file_path);
                $obj['path'] = url('storage/claim/receipt/' . $file);
                $data[] = $obj;
            }
        }
        return response()->json($data);
    }


    public function destroyPicture(Request $request)
    {
        $file_name =  $request->get('filename');
        Photo::where('name', $file_name)->delete();
        $path = public_path('storage/claim/receipt/') . $file_name;
        if (file_exists($path)) {
            unlink($path);
        }
        return response()->json(['success' => $file_name]);
    }

}
