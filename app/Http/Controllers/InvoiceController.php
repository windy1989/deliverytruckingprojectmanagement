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
use PDF;

class InvoiceController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Invoice',
            'content' => 'sales.invoice'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'code',
            'customer_id',
            'down_payment',
            'grandtotal',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');


        $total_data = Invoice::whereDoesnthave('receiptDetail', function($query) {
                $query->whereColumn('receipt_details.invoice_id', 'invoices.id');
            })
            ->orWhereHas('receiptDetail', function($query) {
                $query->whereHas('receipt', function($query) {
                    $query->whereRaw('paid_off < total');
                });
            })
            ->count();

        $query_data = Invoice::whereDoesnthave('receiptDetail', function($query) {
                $query->whereColumn('receipt_details.invoice_id', 'invoices.id');
            })
            ->orWhereHas('receiptDetail', function($query) {
                $query->whereHas('receipt', function($query) {
                    $query->whereRaw('paid_off < total');
                });
            })
            ->where(function($query) use ($search) {
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
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Invoice::whereDoesnthave('receiptDetail', function($query) {
                $query->whereColumn('receipt_details.invoice_id', 'invoices.id');
            })
            ->orWhereHas('receiptDetail', function($query) {
                $query->whereHas('receipt', function($query) {
                    $query->whereRaw('paid_off < total');
                });
            })
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
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->customer->name,
                    'Rp ' . number_format($val->down_payment, 2, ',', '.'),
                    'Rp ' . number_format($val->grandtotal, 2, ',', '.'),
                    date('d M Y', strtotime($val->created_at)),
                    '
                        <a href="' . url('sales/invoice/detail/' . $val->id) . '" class="btn btn-info btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Detail</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="destroy(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus</button>
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

    public function getLetterWay(Request $request)
    {
        $response = [];

        $data = LetterWay::whereHas('order', function($query) use ($request) {
                $query->whereHas('orderCustomerDetail', function($query) use ($request) {
                    $query->where('customer_id', $request->customer_id);
                });

                if($request->start_date && $request->finish_date) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->finish_date);
                }

                if($request->code_order) {
                    $query->where('code', 'LIKE', "%$request->code_order%");

                }
            })
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('invoice_details')
                    ->whereColumn('invoice_details.letter_way_id', 'letter_ways.id');
            })
            ->where('status', 2)
            ->get();

        foreach($data as $d) {
            $price = $d->destination->destinationPrice->last();
            if($price) {
                $price_customer = $price->price_customer;
            } else {
                $price_customer = 0;
            }

            $response[] = [
                'id'         => $d->id,
                'order_code' => $d->order->reference ? $d->order->reference : $d->order->code,
                'number'     => $d->number,
                'qty'        => $d->qty . ' ' . $d->order->unit->name,
                'weight'     => $d->weight . ' Kg',
                'price'      => 'Rp ' . number_format($price_customer, 2, '.', ','),
                'total'      => 'Rp ' . number_format($price_customer * ($d->weight), 2, '.', ',')
            ];
        }

        return response()->json([
            'data'        => $response,
            'max'         => date('Y-m-d'),
            'start_date'  => $request->start_date,
            'finish_date' => $request->finish_date,
            'code_order'  => $request->code_order
        ]);
    }

    public function totalInvoice(Request $request)
    {
        $down_payment = $request->has('down_payment') ? (double)str_replace(',', '', $request->down_payment) : 0;
        $tax          = $request->has('tax') ? (double)$request->tax : 0;
        $discount     = $request->has('discount') ? (double)$request->discount : 0;
        $subtotal     = 0;

        if($request->has('invoice_detail')) {
            foreach($request->invoice_detail as $id) {
                $data  = LetterWay::find($id);
                $price = $data->destination->destinationPrice->last();

                if($price) {
                    $price_customer = $price->price_customer;
                } else {
                    $price_customer = 0;
                }

                $subtotal += $price_customer * ($data->weight);
            }
        }


        $total_discount = ($discount / 100) * $subtotal;
        $totalAfterDisc     = ($subtotal - $total_discount) - $down_payment;
        $total_tax      = ($tax / 100) * $totalAfterDisc;
        $grandtotal = $totalAfterDisc + $total_tax;
        return response()->json([
            'subtotal'   => number_format($subtotal, 2, '.', ','),
            //'grandtotal' => number_format($grandtotal / 100, 2, '.', ',')
			'grandtotal' => number_format($grandtotal, 2, '.', ',')
        ]);
    }

    public function create(Request $request)
    {
        if($request->has('_token')) {
            $validation = Validator::make($request->all(), [
                'customer_id'    => 'required',
                'down_payment'   => 'required',
                'invoice_detail' => 'required',
                'coa_debit'      => 'required',
                'coa_credit'     => 'required'
            ], [
                'customer_id.required'    => 'Mohon memilih customer.',
                'down_payment.required'   => 'Mohon mengisi DP.',
                'invoice_detail.required' => 'Mohon memilih salah satu surat jalan.',
                'coa_debit.required'      => 'Mohon memilih akun.',
                'coa_credit.required'     => 'Mohon memilih pembalik.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $query = Invoice::create([
                    'user_id'      => session('id'),
                    'customer_id'  => $request->customer_id,
                    'code'         => Invoice::generateCode(),
                    'down_payment' => str_replace(',', '', $request->down_payment),
                    'tax'          => str_replace(',', '', $request->tax),
                    'discount'     => str_replace(',', '', $request->discount),
                    'subtotal'     => str_replace(',', '', $request->subtotal),
                    'grandtotal'   => str_replace(',', '', $request->grandtotal)
                ]);

                if($query) {
                    foreach($request->invoice_detail as $id) {
                        $data  = LetterWay::find($id);
                        $price = $data->destination->destinationPrice->last();
                        $total = $price->price_customer * ($data->weight);

                        InvoiceDetail::create([
                            'invoice_id'    => $query->id,
                            'letter_way_id' => $id,
                            'price'         => $price->price_customer,
                            'total'         => $total
                        ]);
                    }

                    Journal::create([
                        'coa_debit'   => $request->coa_debit,
                        'coa_credit'  => $request->coa_credit,
                        'nominal'     => $query->grandtotal,
                        'description' => $query->code
                    ]);

                    activity()
                        ->performedOn(new Invoice())
                        ->causedBy(session('id'))
                        ->withProperties($query)
                        ->log('Menambah data invoice ' . $query->code);

                    session()->flash('success', 'Invoice berhasil di generate.');
                    return redirect()->back();
                } else {
                    session()->flash('failed', 'Invoice gagal di generate.');
                    return redirect()->back();
                }
            }
        } else {
            $data = [
                'title'       => 'Digitrans - Generate Invoice',
                'coa_debit'   => Coa::whereIn('code', [1000,1100,1200])->where('parent_id', 0)->where('status', 1)->get(),
                'coa_credit'  => Coa::where('code', 4000)->where('parent_id', 0)->where('status', 1)->get(),
                'customer'    => Customer::select('id', 'name')->where('status', 1)->get(),
                'start_date'  => $request->start_date,
                'finish_date' => $request->finish_date,
                'content'     => 'sales.invoice_create'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function detail($id)
    {
        $invoice = Invoice::find($id);
        $data    = [
            'title'   => 'Digitrans - ' . $invoice->code,
            'invoice' => $invoice,
            'content' => 'sales.invoice_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function destroy(Request $request)
    {
        $query = Invoice::where('id', $request->id)->forceDelete();
        if($query) {
            InvoiceDetail::where('invoice_id', $request->id)->delete();
            activity()
                ->performedOn(new Invoice())
                ->causedBy(session('id'))
                ->log('Menghapus data invoice');

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

    public function generatePDF($id)
    {
        $invoice = Invoice::find($id);
        $setInv  = explode('/', $invoice->code);
        $data    = [
            'invoice' => $invoice,
            'title'   => 'Digitrans',
            'logo'    => asset('website/logo.png'),
            'date'    => date('m/d/Y')
        ];

        $pdf = PDF::setOptions([
                'dpi'                 => 200,
                'defaultFont'         => 'sans-serif',
                'enable_html5_parser' => false,
                'enable_remote'       => true,
                'enable_javascript'   => true,
                'enable_php'          => false
            ])
            ->loadView('sales.generate_invoice_pdf', $data);

        return $pdf->stream('Digitrans - DIGI_INV_' . $setInv[2] . '_' . $setInv[3] . '_' . $setInv[4]);
    }


}
