<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\Invoice;
use App\Models\Journal;
use App\Models\Customer;
use App\Models\LetterWay;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportInvoiceController extends Controller {

    public function index()
    {
        $data = [
            'title'    => 'Digitrans - Laporan Invoice',
            'customer' => Customer::select('id', 'name')->where('status', 1)->get(),
            'content'  => 'report.invoice'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {

        $column = [
            'id',
            'code',
            'customer_id',
            'paid',
            'down_payment',
            'grandtotal',
            'created_at',
            'deleted_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Invoice::withTrashed()
            ->count();

        $query_data = Invoice::withTrashed()
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

                if($request->paid) {
                    $query->whereHas('receiptDetail', function($query) use ($request) {
                        $query->whereHas('receipt', function($query) use ($request) {
                            if($request->paid == 1) {
                                $query->whereRaw('paid_off >= total');
                            } else {
                                $query->whereRaw('paid_off < total');
                            }
                        });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Invoice::withTrashed()
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

                if($request->paid) {
                    $query->whereHas('receiptDetail', function($query) use ($request) {
                        $query->whereHas('receipt', function($query) use ($request) {
                            if($request->paid == 1) {
                                $query->whereRaw('paid_off >= total');
                            } else {
                                $query->whereRaw('paid_off < total');
                            }
                        });
                    });
                }
            })
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

                if($val->receiptDetail) {
                    if($val->receiptDetail->receipt->paid_off >= $val->receiptDetail->receipt->total) {
                        $paid = 'Lunas';
                    } else {
                        $paid = 'Belum Lunas';
                    }
                } else {
                    $paid = '-';
                }

                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->customer->name,
                    $paid,
                    'Rp ' . number_format($val->down_payment, 2, ',', '.'),
                    'Rp ' . number_format($val->grandtotal, 2, ',', '.'),
                    date('d M Y', strtotime($val->created_at)),
                    $status,
                    '
                        <a href="' . url('report/invoice/detail/' . $val->id) . '" class="btn btn-info btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Detail</a>
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

    public function detail($id)
    {
        $invoice = Invoice::find($id);
        $data    = [
            'title'   => 'Digitrans - ' . $invoice->code,
            'invoice' => $invoice,
            'content' => 'report.invoice_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
