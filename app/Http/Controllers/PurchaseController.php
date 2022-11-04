<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Claim;
use App\Models\Vendor;
use App\Models\Journal;
use App\Models\LetterWay;
use App\Models\Repayment;
use App\Models\ClaimDetail;
use App\Models\Coa;
use App\Models\Photo;
use Illuminate\Http\Request;
use App\Models\RepaymentDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Pembelian',
            'vendor'  => Vendor::where('status', 1)->get(),
            'coa'     => Coa::where('parent_id', 0)->where('status', 1)->get(),
            'content' => 'purchase'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'vendor_id',
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

        $total_data = Repayment::count();

        $query_data = Repayment::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search, $request) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('vendor', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }

            if ($request->vendor_id) {
                $query->where('vendor_id', $request->vendor_id);
            }

            if ($request->start_date && $request->finish_date) {
                $query->whereDate('created_at', '>=', $request->start_date)
                    ->whereDate('created_at', '<=', $request->finish_date);
            } else if ($request->start_date) {
                $query->whereDate('created_at', $request->start_date);
            } else if ($request->finish_date) {
                $query->whereDate('created_at', $request->finish_date);
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Repayment::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('vendor', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }

            if ($request->vendor_id) {
                $query->where('vendor_id', $request->vendor_id);
            }

            if ($request->start_date && $request->finish_date) {
                $query->whereDate('created_at', '>=', $request->start_date)
                    ->whereDate('created_at', '<=', $request->finish_date);
            } else if ($request->start_date) {
                $query->whereDate('created_at', $request->start_date);
            } else if ($request->finish_date) {
                $query->whereDate('created_at', $request->finish_date);
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach ($query_data as $val) {
                $rest            = ($val->total - $val->claim) - $val->paid_off;
                $total_repayment = $val->total - $val->claim;
                $current_date    = strtotime(date('Y-m-d'));
                $due_date        = strtotime($val->due_date);

                if ($current_date == $due_date) {
                    $status = '<span class="text-info font-weight-bold">Jatuh tempo hari ini</span>';
                } else if ($current_date > $due_date) {
                    $status = '<span class="text-danger font-weight-bold">Lewat jatuh tempo</span>';
                } else {
                    $day    = ($due_date - $current_date) / 60 / 60 / 24;
                    $status = '<span class="text-success font-weight-bold">Jatuh tempo ' . $day . ' hari lagi</span>';
                }

                if ($val->paid_off > 0 && $val->paid_off < $total_repayment) {
                    $paidable = '<span class="text-warning font-weight-bold">Belum Lunas</span>';
                    $btn_paid = '<button type="button" class="btn btn-success btn-sm" onclick="paid(' . $val->id . ', ' . $val->paid_off . ', `' . $val->code . '`, ' . $total_repayment . ', ' . $rest . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Bayar</button>';
                } else {
                    $paidable = '<span class="text-danger font-weight-bold">Belum Dibayar</span>';
                    $btn_paid = '<button type="button" class="btn btn-success btn-sm" onclick="paid(' . $val->id . ', ' . $val->paid_off . ', `' . $val->code . '`, ' . $total_repayment . ', ' . $rest . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Bayar</button>';
                }

                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->vendor->name,
                    $paidable,
                    $status,
                    date('F d, Y', strtotime($val->due_date)),
                    'Rp ' . number_format($total_repayment, 2, ',', '.'),
                    'Rp ' . number_format($val->paid_off, 2, ',', '.'),
                    date('d M Y', strtotime($val->created_at)),
                    '
                        ' . $btn_paid . '
                        <a href="' . url('purchase/claim/' . $val->id) . '" class="btn btn-warning btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg> Klaim</a>
                        <a href="' . url('purchase/detail/' . $val->id) . '" class="btn btn-secondary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> Detail</a>
                    '
                ];
                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if ($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if ($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function grandtotal(Request $request)
    {
        $repayment = Repayment::where(function ($query) use ($request) {
            if ($request->vendor_id) {
                $query->where('vendor_id', $request->vendor_id);
            }

            if ($request->start_date && $request->finish_date) {
                $query->whereDate('created_at', '>=', $request->start_date)
                    ->whereDate('created_at', '<=', $request->finish_date);
            } else if ($request->start_date) {
                $query->whereDate('created_at', $request->start_date);
            } else if ($request->finish_date) {
                $query->whereDate('created_at', $request->finish_date);
            }
        })
            ->sum('total');

        return response()->json('Rp ' . number_format($repayment, 2, ',', '.'));
    }

    public function getLetterWay(Request $request)
    {
        $response = [];
        $data     = LetterWay::whereHas('order', function ($query) use ($request) {
            $query->where('vendor_id', $request->vendor_id);
        })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('repayment_details')
                    ->whereColumn('repayment_details.letter_way_id', 'letter_ways.id');
            })
            ->groupBy('id')
            ->get();

        foreach ($data as $d) {
            $statsTelat = "";
            $pecah = "";
            if ($d->legalize_received_date > $d->order->date) {
                $datetime1 = new DateTime($d->legalize_received_date);
                $datetime2 = new DateTime($d->order->date);
                $interval = $datetime1->diff($datetime2);
                $statsTelat = 'Telat: ' . $interval->format('%a') . "Hari";
            } else {
                $statsTelat = '-';
            }
            $price      = $d->destination->destinationPrice->last();
            $response[] = [
                'id'         => $d->id,
                'order_code' => $d->order->code,
                'number'     => $d->number,
                'qty'        => $d->qty - $d->ttbr_qty . ' ' . $d->order->unit->name,
                'weight'     => $d->weight . ' Kg',
                'price'      => 'Rp ' . number_format($price->price_vendor, 2, ',', '.'),
                'total'      => 'Rp ' . number_format(($price->price_vendor * ($d->weight)), 2, ',', '.'),
                'pecah'      => $d->ttbr_qty . ' ' . $d->order->unit->name,
                'status_telat' => $statsTelat,
            ];
        }

        return response()->json($response);
    }

    public function totalRepayment(Request $request)
    {
        $total = 0;
        $tax          = $request->has('tax') ? (float)$request->tax : 0;
        if ($request->has('repayment_detail')) {
            foreach ($request->repayment_detail as $rd) {
                $letter_way = LetterWay::find($rd);
                $price      = $letter_way->destination->destinationPrice->last();
                $total     += $price->price_vendor * ($letter_way->weight);
            }
        }
        $total_tax      = ($tax / 100) * $total;
        $grandtotal = $total + $total_tax;
        return response()->json([
            'newtotal' => number_format($grandtotal, 2, ',', '.'),
            'total' => $grandtotal
        ]);
    }

    public function create(Request $request)
    {
        session()->forget('checked');
        if ($request->has('_token')) {
            $validation = Validator::make($request->all(), [
                'vendor_id'        => 'required',
                'due_date'         => 'required',
                'repayment_detail' => 'required'
            ], [
                'vendor_id.required'        => 'Mohon memilih vendor.',
                'due_date.required'         => 'Jatuh tempo tidak boleh kosong.',
                'repayment_detail.required' => 'Mohon memilih salah satu surat jalan.'
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $query = Repayment::create([
                    'user_id'     => session('id'),
                    'vendor_id'   => $request->vendor_id,
                    'code'        => Repayment::generateCode(),
                    'reference'   => $request->reference,
                    'tax'         => str_replace(',', '', $request->tax),
                    'paid_off'    => str_replace(',', '', $request->paid_off),
                    'due_date'    => $request->due_date,
                    'total'       => str_replace(',', '', $request->total)
                ]);

                if ($query) {
                    foreach ($request->repayment_detail as $rd) {
                        $data  = LetterWay::find($rd);
                        $price = $data->destination->destinationPrice->last();
                        $total = $price->price_vendor * ($data->weight);

                        RepaymentDetail::create([
                            'repayment_id'  => $query->id,
                            'letter_way_id' => $rd,
                            'price'         => $price->price_vendor,
                            'total'         => $total
                        ]);
                    }

                    Journal::create([
                        'coa_debit'   => 5001,
                        'coa_credit'  => 10002,
                        'nominal'     => $query->total,
                        'description' => $query->code
                    ]);

                    if ($query->paid_off > 0) {
                        Journal::create([
                            'coa_debit'   => 10002,
                            'coa_credit'  => 2001,
                            'nominal'     => $query->paid_off,
                            'description' => $query->code
                        ]);
                    }

                    activity()
                        ->performedOn(new Repayment())
                        ->causedBy(session('id'))
                        ->withProperties($query)
                        ->log('Menambah data pembelian ' . $query->code);

                    session()->flash('success', 'Pelunasan berhasil di buat.');
                    return redirect()->back();
                } else {
                    session()->flash('failed', 'Pelunasan gagal di buat.');
                    return redirect()->back();
                }
            }
        } else {
            $data = [
                'title'    => 'Digitrans - Buat Pelunasan',
                'vendor'   => Vendor::select('id', 'name')->where('status', 1)->get(),
                'content'  => 'purchase_create'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function claim(Request $request, $id)
    {
        $repayment  = Repayment::find($id);
        $letter_way = [];

        foreach ($repayment->repaymentDetail as $rd) {
            $deadline        = $rd->letterWay->order->deadline;
            $order_date      = date('Y-m-d', strtotime("+$deadline day", strtotime($rd->letterWay->order->date)));
            $letter_way_date = date('Y-m-d', strtotime($rd->letterWay->updated_at));
            $ttbr_qty        = $rd->letterWay->ttbr_qty;
            $difference_late = strtotime($letter_way_date) - strtotime($order_date);
            $total_late      = $difference_late / 60 / 60 / 24;

            if ($ttbr_qty > 0) {
                if ($repayment->claims) {
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
            } else if ($letter_way_date > $order_date) {
                if ($repayment->claims) {
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

        if ($request->has('_token')) {
            $query = Claim::create([
                'claimable_type' => 'repayments',
                'claimable_id'   => $repayment->id,
                'date'           => date('Y-m-d'),
                'description'    => $request->description
            ]);

            if ($query) {
                $total_claim = 0;
                if ($request->claim_detail_letter_way_id) {
                    foreach ($request->claim_detail_letter_way_id as $key => $cdlwi) {
                        $letter_way       = LetterWay::find($cdlwi);
                        $claim_rupiah     = (float)str_replace(',', '', $request->claim_detail_rupiah[$key]);
                        $claim_tolerance  = (float)str_replace(',', '', $request->claim_detail_tolerance[$key]);
                        $claim_percentage = (float)$request->claim_detail_percentage[$key];

                        if ($request->claim_type[$key] == 'destroy') {
                            $total_claim += $claim_rupiah - $claim_tolerance;
                        } else {
                            $claim_late   = $request->claim_detail_late[$key];
                            $price        = $letter_way->destination->destinationPrice->last();
                            $price_vendor = $price ? $price->price_vendor : 0;
                            $calculate_by = $request->flag == 1 ? $letter_way->weight : $letter_way->qty;
                            $total_claim += $total_late * ($calculate_by * $price_vendor) * ($claim_percentage / 100);
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

                $repayment->update(['claim' => $total_claim]);
                activity()
                    ->performedOn(new Claim())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data klaim pembelian');

                session()->flash('success', 'Klaim pembelian berhasil dibuat.');
                return redirect()->back();
            } else {
                session()->flash('failed', 'Klaim pembelian gagal dibuat.');
                return redirect()->back();
            }
        } else {
            $data = [
                'title'      => 'Klaim - ' . $repayment->code,
                'repayment'  => $repayment,
                'letter_way' => $letter_way,
                'content'    => 'purchase_claim'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function paid(Request $request)
    {
        $repayment = Repayment::find($request->id);
        $paid_off  = $repayment->paid_off + (float)str_replace(',', '', $request->paid_off);
        $query     = Repayment::where('id', $request->id)->update(['paid_off' => $paid_off]);
        if ($query) {
            Journal::create([
                'coa_debit'   => $request->coa_debit,
                'coa_credit'  => $request->coa_credit,
                'nominal'     => str_replace(',', '', $request->paid_off),
                'description' => $repayment->code
            ]);

            activity()
                ->performedOn(new Repayment())
                ->causedBy(session('id'))
                ->log('Melakukan pembayaran tagihan pembelian ' . $repayment->code);

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
        $repayment = Repayment::find($id);
        $data      = [
            'title'     => 'Digitrans - ' . $repayment->code,
            'repayment' => $repayment,
            'content'   => 'purchase_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function addPicture(Request $request)
    {
        $file_name =  $request->file('file')->getClientOriginalName();
        $query = Photo::create([
            'photo_type'            => $request->type,
            'photo_id'            => $request->photoable_id,
            'name'            => $request->file('file') ? $request->file('file')->getClientOriginalName() : '',
            'path'            => $request->file('file') ? $request->file('file')->storeAs('public/claim', $file_name) : '',
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
        $storeFolder = public_path('storage/claim');
        $file_path = public_path('storage/claim/');
        $files = scandir($storeFolder);
        $data = [];
        foreach ($files as $file) {
            if (in_array($file, $tableImages)) {
                $obj['name'] = $file;
                $file_path = public_path('storage/claim/') . $file;
                $obj['size'] = filesize($file_path);
                $obj['path'] = url('storage/claim/' . $file);
                $data[] = $obj;
            }
        }
        return response()->json($data);
    }


    public function destroyPicture(Request $request)
    {
        $file_name =  $request->get('filename');
        Photo::where('name', $file_name)->delete();
        $path = public_path('storage/claim/') . $file_name;
        if (file_exists($path)) {
            unlink($path);
        }
        return response()->json(['success' => $file_name]);
    }
}
