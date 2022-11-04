<?php

namespace App\Http\Controllers;

use App\Models\LetterWay;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportLetterWayController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Laporan Surat Jalan',
            'content' => 'report.letter_way'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'order_id',
            'number',
            'customer_id'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = LetterWay::count();

        $query_data = LetterWay::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('number', 'like', "%$search%")
                            ->orWhereHas('order', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%")
                                    ->orWhereHas('orderCustomerDetail', function($query) use ($search) {
                                        $query->where('code', 'like', "%$search%")
                                        ->orWhereHas('customer', function($query) use ($search) {
                                            $query->where('name', 'like', "%$search%");
                                        });
                                    });
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereBetween('received_date', [$request->start_date, $request->finish_date]);

                } else if($request->start_date) {
                    $query->whereDate('received_date', $request->start_date);

                } else if($request->finish_date) {
                    $query->whereDate('received_date', $request->finish_date);
                }

                if($request->order_id) {
                    $query->where('order_id', $request->order_id);
                }

                if($request->status) {
                    if($request->status == 1) {
                        $query->where(function($query) use ($request) {
                            $query->where(function($query) {
                                    $query->whereNotNull('number')
                                        ->orWhereNotNull('destination_id')
                                        ->orWhereNotNull('weight')
                                        ->orWhereNotNull('qty');
                                })
                                ->whereNull('received_date')
                                ->whereNull('send_back_attachment')
                                ->whereNull('legalize_attachment')
                                ->whereNull('legalize_received_date')
                                ->whereNull('legalize_send_back_attachment')
                                ->whereNull('legalize_send_back_received_date')
                                ->whereNull('ttbr_qty')
                                ->whereNull('ttbr_attachment');
                        });
                    } else if($request->status == 2) {
                        $query->where(function($query) use ($request) {
                            $query->where(function($query) {
                                    $query->whereNotNull('send_back_attachment')
                                        ->orWhereNotNull('ttbr_qty')
                                        ->orWhereNotNull('received_date');
                                })
                                ->whereNull('legalize_attachment')
                                ->whereNull('legalize_received_date')
                                ->whereNull('legalize_send_back_attachment')
                                ->whereNull('legalize_send_back_received_date')
                                ->whereNull('ttbr_attachment');
                        });
                    } else if($request->status == 3) {
                        $query->where(function($query) use ($request) {
                            $query->where(function($query) {
                                    $query->whereNotNull('legalize_attachment')
                                        ->orWhereNotNull('legalize_received_date');
                                })
                                ->whereNull('legalize_send_back_attachment')
                                ->whereNull('legalize_send_back_received_date')
                                ->whereNull('ttbr_attachment');
                        });
                    } else if($request->status == 4) {
                        $query->where(function($query) use ($request) {
                            $query->where(function($query) {
                                    $query->whereNotNull('legalize_send_back_attachment')
                                        ->orWhereNotNull('legalize_send_back_received_date');
                                })
                                ->whereNull('ttbr_attachment');
                        });
                    } else if($request->status == 5) {
                        $query->where(function($query) use ($request) {
                            $query->whereNotNull('ttbr_attachment');
                        });
                    }
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = LetterWay::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('number', 'like', "%$search%")
                            ->orWhereHas('order', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%")
                                    ->orWhereHas('orderCustomerDetail', function($query) use ($search) {
                                        $query->where('code', 'like', "%$search%")
                                        ->orWhereHas('customer', function($query) use ($search) {
                                            $query->where('name', 'like', "%$search%");
                                        });
                                    });
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereBetween('received_date', [$request->start_date, $request->finish_date]);
                } else if($request->start_date) {
                    $query->whereDate('received_date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('received_date', $request->finish_date);
                }

                if($request->order_id) {
                    $query->where('order_id', $request->order_id);
                }

                if($request->status) {
                    if($request->status == 1) {
                        $query->where(function($query) use ($request) {
                            $query->where(function($query) {
                                    $query->whereNotNull('number')
                                        ->orWhereNotNull('destination_id')
                                        ->orWhereNotNull('weight')
                                        ->orWhereNotNull('qty');
                                })
                                ->whereNull('received_date')
                                ->whereNull('send_back_attachment')
                                ->whereNull('legalize_attachment')
                                ->whereNull('legalize_received_date')
                                ->whereNull('legalize_send_back_attachment')
                                ->whereNull('legalize_send_back_received_date')
                                ->whereNull('ttbr_qty')
                                ->whereNull('ttbr_attachment');
                        });
                    } else if($request->status == 2) {
                        $query->where(function($query) use ($request) {
                            $query->where(function($query) {
                                    $query->whereNotNull('send_back_attachment')
                                        ->orWhereNotNull('ttbr_qty')
                                        ->orWhereNotNull('received_date');
                                })
                                ->whereNull('legalize_attachment')
                                ->whereNull('legalize_received_date')
                                ->whereNull('legalize_send_back_attachment')
                                ->whereNull('legalize_send_back_received_date')
                                ->whereNull('ttbr_attachment');
                        });
                    } else if($request->status == 3) {
                        $query->where(function($query) use ($request) {
                            $query->where(function($query) {
                                    $query->whereNotNull('legalize_attachment')
                                        ->orWhereNotNull('legalize_received_date');
                                })
                                ->whereNull('legalize_send_back_attachment')
                                ->whereNull('legalize_send_back_received_date')
                                ->whereNull('ttbr_attachment');
                        });
                    } else if($request->status == 4) {
                        $query->where(function($query) use ($request) {
                            $query->where(function($query) {
                                    $query->whereNotNull('legalize_send_back_attachment')
                                        ->orWhereNotNull('legalize_send_back_received_date');
                                })
                                ->whereNull('ttbr_attachment');
                        });
                    } else if($request->status == 5) {
                        $query->where(function($query) use ($request) {
                            $query->whereNotNull('ttbr_attachment');
                        });
                    }
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {

            $nomor = $start + 1;
            foreach($query_data as $val) {
                $cust_nm="";
                foreach($val->order->orderCustomerDetail as $key => $char)
                {
                    $cust_nm .= $char->customer->name;
                }
                if($val->status == 1) {
                    $status   = 'Diproses';
                }
                else
                {
                    $status   = 'Finish';
                }
                $response['data'][] = [

                    $nomor,
                    $val->order->code,
                    $val->number,
                    $cust_nm,
                    $status,
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
        $data           = LetterWay::find($request->id);
        $destination_id = $data->destination->cityOrigin->name . ' - ' . $data->destination->cityDestination->name;


        $send_back_attachment = '<a href="' . $data->sendBackAttachment() . '" data-lightbox="' . $data->sendBackAttachment() . '" data-title="Lampiran Surat Jalan Balik"><img src="' . $data->sendBackAttachment() . '" style="max-width:70px; max-height:70px;" class="img-fluid img-thumbnail"></a>';

        $legalize_attachment = '<a href="' . $data->legalizeAttachment() . '" data-lightbox="' . $data->legalizeAttachment() . '" data-title="Lampiran Surat Jalan Legalisir"><img src="' . $data->legalizeAttachment() . '" style="max-width:70px; max-height:70px;" class="img-fluid img-thumbnail"></a>';

        $legalize_send_back_attachment = '<a href="' . $data->legalizeSendBackAttachment() . '" data-lightbox="' . $data->legalizeSendBackAttachment() . '" data-title="Lampiran Surat Jalan Legalisir Balik"><img src="' . $data->legalizeSendBackAttachment() . '" style="max-width:70px; max-height:70px;" class="img-fluid img-thumbnail"></a>';

        $ttbr_attachment = '<a href="' . $data->ttbrAttachment() . '" data-lightbox="' . $data->ttbrAttachment() . '" data-title="Lampiran Surat Jalan TTBR"><img src="' . $data->ttbrAttachment() . '" style="max-width:70px; max-height:70px;" class="img-fluid img-thumbnail"></a>';

        if(is_null($data->received_date)) {
            $dateReceived = '-';
        } else {
            $dateReceived = date('d-m-Y', strtotime($data->received_date));
        }

        if(is_null($data->legalize_received_date)) {
            $legalize_received_date = '-';
        } else {
            $legalize_received_date = date('d-m-Y', strtotime($data->legalize_received_date));
        }

        if(is_null($data->legalize_send_back_received_date)) {
            $legalize_send_back_received_date = '-';
        } else {
            $legalize_send_back_received_date = date('d-m-Y', strtotime($data->legalize_send_back_received_date));
        }

        return response()->json([
            'number'                           => ': ' . $data->number,
            'destination_id'                   => ': ' . $destination_id,
            'weight'                           => ': ' . $data->weight . ' Kg',
            'qty'                              => ': ' . $data->qty . ' ' . $data->order->unit->name,
            'received_date'                    => ': ' . $dateReceived,
            'send_back_attachment'             => ': ' . $send_back_attachment,
            'legalize_attachment'              => ': ' . $legalize_attachment,
            'legalize_received_date'           => ': ' . $legalize_received_date,
            'legalize_send_back_attachment'    => ': ' . $legalize_send_back_attachment,
            'legalize_send_back_received_date' => ': ' . $legalize_send_back_received_date,
            'ttbr_qty'                         => ': ' . $data->ttbr_qty,
            'ttbr_attachment'                  => ': ' . $ttbr_attachment
        ]);
    }

}
