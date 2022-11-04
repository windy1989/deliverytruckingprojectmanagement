<?php

namespace App\Http\Controllers;

use App\Exports\RecapExport;
use App\Exports\RecapVendorExport;
use App\Models\Customer;
use App\Models\LetterWay;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportRecapController extends Controller
{
    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Laporan Surat Jalan',
            'vendor'   => Vendor::all(),
            'customer'   => Customer::all(),
            'content' => 'report.recap'
        ];

        return view('layouts.index', ['data' => $data]);
    }


    public function datatable(Request $request)
    {

        $column = [
            'id',
            'order_id',
            'order_id',
            'number',
            'customer_id',
            'vendor_id',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = LetterWay::count();

        $query_data = LetterWay::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search, $request) {
                    $query->where('number', 'like', "%$search%")
                        ->orWhereHas('order', function ($query) use ($search) {
                            $query->where('code', 'like', "%$search%")
                                ->orWhereHas('orderCustomerDetail', function ($query) use ($search) {
                                    $query->where('code', 'like', "%$search%")
                                        ->orWhereHas('customer', function ($query) use ($search) {
                                            $query->where('name', 'like', "%$search%");
                                        });
                                });
                        });

                    $query->where('number', 'like', "%$search%")
                        ->orWhereHas('order', function ($query) use ($search) {
                            $query->where('code', 'like', "%$search%")
                                ->orWhereHas('vendor', function ($query) use ($search) {
                                    $query->where('name', 'like', "%$search%");
                                });
                        });
                });
            }

            if ($request->start_date && $request->finish_date) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereBetween('date', [$request->start_date, $request->finish_date]);
                });
            } else if ($request->start_date) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereDate('date', $request->start_date);
                });
            } else if ($request->finish_date) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereDate('date', $request->finish_date);
                });
            }
            if ($request->vendor_id) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereHas('vendor', function ($query) use ($request) {
                        $query->where('id', $request->vendor_id);
                    });
                });
            }

            if ($request->customer_id) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                        $query->whereHas('customer', function ($query) use ($request) {
                            $query->where('customer_id', $request->customer_id);
                        });
                    });
                });
            }
            if ($request->hasInvoice) {
                if ($request->hasInvoice === "no") {
                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                    });
                } else {
                    $query->whereHas('invoiceDetail', function ($query) {
                    });
                }
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = LetterWay::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search, $request) {
                    $query->where('number', 'like', "%$search%")
                        ->orWhereHas('order', function ($query) use ($search) {
                            $query->where('code', 'like', "%$search%")
                                ->orWhereHas('orderCustomerDetail', function ($query) use ($search) {
                                    $query->where('code', 'like', "%$search%")
                                        ->orWhereHas('customer', function ($query) use ($search) {
                                            $query->where('name', 'like', "%$search%");
                                        });
                                });
                        });
                    $query->where('number', 'like', "%$search%")
                        ->orWhereHas('order', function ($query) use ($search) {
                            $query->where('code', 'like', "%$search%")
                                ->orWhereHas('vendor', function ($query) use ($search) {
                                    $query->where('name', 'like', "%$search%");
                                });
                        });
                });
            }


            if ($request->start_date && $request->finish_date) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereBetween('date', [$request->start_date, $request->finish_date]);
                });
            } else if ($request->start_date) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereDate('date', $request->start_date);
                });
            } else if ($request->finish_date) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereDate('date', $request->finish_date);
                });
            }
            if ($request->vendor_id) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereHas('vendor', function ($query) use ($request) {
                        $query->where('id', $request->vendor_id);
                    });
                });
            }
            if ($request->customer_id) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                        $query->whereHas('customer', function ($query) use ($request) {
                            $query->where('customer_id', $request->customer_id);
                        });
                    });
                });
            }
            if ($request->hasInvoice) {
                if ($request->hasInvoice === "no") {
                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                    });
                } else {
                    $query->whereHas('invoiceDetail', function ($query) {
                    });
                }
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {

            $nomor = $start + 1;
            foreach ($query_data as $val) {
                $cust_nm = "";
                foreach ($val->order->orderCustomerDetail as $key => $char) {
                    $cust_nm .= $char->customer->name;
                }

                $response['data'][] = [

                    $nomor,
                    $val->order->code,
                    date(
                        'd M Y',
                        strtotime($val->order->date)
                    ),
                    $val->number,
                    $cust_nm,
                    $val->order->vendor->name,

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


    public function detail(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'start_date'                        => 'required',
        ], [
            'start_date.required'               => 'Mohon mengisi tanggal order.',
        ]);


        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        } else {
            if ($request->start_date || $request->finish_date || $request->vendor_id || $request->customer_id) {
                if ($request->vendor_id && $request->customer_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    if ($request->start_date && $request->finish_date) {
                                        $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    } else if ($request->start_date) {
                                        $query->whereDate('date', $request->start_date)
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    } else if ($request->finish_date) {
                                        $query->whereDate('date', $request->finish_date)
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    }
                                });
                                $query->WhereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    $query->whereHas('customer', function ($query) use ($request) {
                                        $query->where('customer_id', $request->customer_id);
                                    });
                                });
                                $query->WhereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else if ($request->vendor_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                if ($request->start_date && $request->finish_date) {
                                    $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                } else if ($request->start_date) {
                                    $query->whereDate('date', $request->start_date)
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                } else if ($request->finish_date) {
                                    $query->whereDate('date', $request->finish_date)
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                }
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else if ($request->customer_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                if ($request->start_date && $request->finish_date) {
                                    $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                } else if ($request->start_date) {
                                    $query->whereDate('date', $request->start_date)
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                } else if ($request->finish_date) {
                                    $query->whereDate('date', $request->finish_date)
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                }
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    $query->whereHas('customer', function ($query) use ($request) {
                                        $query->where('customer_id', $request->customer_id);
                                    });
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else {
                    $recap = LetterWay::where(function ($query) use ($request) {
                        $query->whereHas('order', function ($query) use ($request) {
                            if ($request->start_date && $request->finish_date) {
                                $query->whereBetween('date', [$request->start_date, $request->finish_date]);
                            } else if ($request->start_date) {
                                $query->whereDate('date', $request->start_date);
                            } else if ($request->finish_date) {
                                $query->whereDate('date', $request->finish_date);
                            }
                        });
                        if ($request->hasInvoice) {
                            if ($request->hasInvoice === "no") {
                                $query->whereDoesntHave('invoiceDetail', function ($query) {
                                });
                            } else {
                                $query->whereHas('invoiceDetail', function ($query) {
                                });
                            }
                        }
                    })->orderBy('order_id')
                        ->get();
                }
            } else {
                $recap = LetterWay::all();
            }

            $data = [
                'data'         => $recap,
                'title'        => 'Recap Laporan',
                'content'      => 'report.detail_recap'
            ];
            return view('layouts.index', ['data' => $data]);
        }
    }


    public function detailVendor(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'start_date'                        => 'required',
        ], [
            'start_date.required'               => 'Mohon mengisi tanggal order.',
        ]);


        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        } else {
            if ($request->start_date || $request->finish_date || $request->vendor_id || $request->customer_id) {
                if ($request->vendor_id && $request->customer_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    if ($request->start_date && $request->finish_date) {
                                        $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    } else if ($request->start_date) {
                                        $query->whereDate('date', $request->start_date)
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    } else if ($request->finish_date) {
                                        $query->whereDate('date', $request->finish_date)
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    }
                                });
                                $query->WhereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    $query->whereHas('customer', function ($query) use ($request) {
                                        $query->where('customer_id', $request->customer_id);
                                    });
                                });
                                $query->WhereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else if ($request->vendor_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                if ($request->start_date && $request->finish_date) {
                                    $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                } else if ($request->start_date) {
                                    $query->whereDate('date', $request->start_date)
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                } else if ($request->finish_date) {
                                    $query->whereDate('date', $request->finish_date)
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                }
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else if ($request->customer_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                if ($request->start_date && $request->finish_date) {
                                    $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                } else if ($request->start_date) {
                                    $query->whereDate('date', $request->start_date)
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                } else if ($request->finish_date) {
                                    $query->whereDate('date', $request->finish_date)
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                }
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    $query->whereHas('customer', function ($query) use ($request) {
                                        $query->where('customer_id', $request->customer_id);
                                    });
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else {
                    $recap = LetterWay::where(function ($query) use ($request) {
                        $query->whereHas('order', function ($query) use ($request) {
                            if ($request->start_date && $request->finish_date) {
                                $query->whereBetween('date', [$request->start_date, $request->finish_date]);
                            } else if ($request->start_date) {
                                $query->whereDate('date', $request->start_date);
                            } else if ($request->finish_date) {
                                $query->whereDate('date', $request->finish_date);
                            }
                        });
                        if ($request->hasInvoice) {
                            if ($request->hasInvoice === "no") {
                                $query->whereDoesntHave('invoiceDetail', function ($query) {
                                });
                            } else {
                                $query->whereHas('invoiceDetail', function ($query) {
                                });
                            }
                        }
                    })->orderBy('order_id')
                        ->get();
                }
            } else {
                $recap = LetterWay::all();
            }


            $data = [
                'data'         => $recap,
                'title'        => 'Recap Laporan',
                'content'      => 'report.detail_recap_vendor'
            ];
            return view('layouts.index', ['data' => $data]);
        }
    }

    public function print(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'start_date'                        => 'required',
        ], [
            'start_date.required'               => 'Mohon mengisi tanggal order.',
        ]);


        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        } else {
            if ($request->start_date || $request->finish_date || $request->vendor_id || $request->customer_id) {
                if ($request->vendor_id && $request->customer_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    if ($request->start_date && $request->finish_date) {
                                        $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    } else if ($request->start_date) {
                                        $query->whereDate('date', $request->start_date)
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    } else if ($request->finish_date) {
                                        $query->whereDate('date', $request->finish_date)
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    }
                                });
                                $query->WhereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    $query->whereHas('customer', function ($query) use ($request) {
                                        $query->where('customer_id', $request->customer_id);
                                    });
                                });
                                $query->WhereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else if ($request->vendor_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                if ($request->start_date && $request->finish_date) {
                                    $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                } else if ($request->start_date) {
                                    $query->whereDate('date', $request->start_date)
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                } else if ($request->finish_date) {
                                    $query->whereDate('date', $request->finish_date)
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                }
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else if ($request->customer_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                if ($request->start_date && $request->finish_date) {
                                    $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                } else if ($request->start_date) {
                                    $query->whereDate('date', $request->start_date)
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                } else if ($request->finish_date) {
                                    $query->whereDate('date', $request->finish_date)
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                }
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    $query->whereHas('customer', function ($query) use ($request) {
                                        $query->where('customer_id', $request->customer_id);
                                    });
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else {
                    $recap = LetterWay::where(function ($query) use ($request) {
                        $query->whereHas('order', function ($query) use ($request) {
                            if ($request->start_date && $request->finish_date) {
                                $query->whereBetween('date', [$request->start_date, $request->finish_date]);
                            } else if ($request->start_date) {
                                $query->whereDate('date', $request->start_date);
                            } else if ($request->finish_date) {
                                $query->whereDate('date', $request->finish_date);
                            }
                        });
                        if ($request->hasInvoice) {
                            if ($request->hasInvoice === "no") {
                                $query->whereDoesntHave('invoiceDetail', function ($query) {
                                });
                            } else {
                                $query->whereHas('invoiceDetail', function ($query) {
                                });
                            }
                        }
                    })->orderBy('order_id')
                        ->get();
                }
            } else {
                $recap = LetterWay::all();
            }


            $data = [
                'data'         => $recap,
                'title'        => 'Recap Laporan',
            ];
            return (new RecapExport($data))->download('recap.xlsx');
        }
    }


    public function printVendor(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'start_date'                        => 'required',
        ], [
            'start_date.required'               => 'Mohon mengisi tanggal order.',
        ]);


        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        } else {
            if ($request->start_date || $request->finish_date || $request->vendor_id || $request->customer_id) {
                if ($request->vendor_id && $request->customer_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    if ($request->start_date && $request->finish_date) {
                                        $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    } else if ($request->start_date) {
                                        $query->whereDate('date', $request->start_date)
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    } else if ($request->finish_date) {
                                        $query->whereDate('date', $request->finish_date)
                                            ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                                $query->whereHas('customer', function ($query) use ($request) {
                                                    $query->where('customer_id', $request->customer_id);
                                                });
                                            });
                                    }
                                });
                                $query->WhereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    $query->whereHas('customer', function ($query) use ($request) {
                                        $query->where('customer_id', $request->customer_id);
                                    });
                                });
                                $query->WhereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else if ($request->vendor_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                if ($request->start_date && $request->finish_date) {
                                    $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                } else if ($request->start_date) {
                                    $query->whereDate('date', $request->start_date)
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                } else if ($request->finish_date) {
                                    $query->whereDate('date', $request->finish_date)
                                        ->WhereHas('vendor', function ($query) use ($request) {
                                            $query->where('vendor_id', $request->vendor_id);
                                        });
                                }
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('vendor', function ($query) use ($request) {
                                    $query->where('vendor_id', $request->vendor_id);
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else if ($request->customer_id) {
                    if ($request->start_date || $request->finish_date || $request->start_date && $request->finish_date) {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                if ($request->start_date && $request->finish_date) {
                                    $query->whereBetween('date', [$request->start_date, $request->finish_date])
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                } else if ($request->start_date) {
                                    $query->whereDate('date', $request->start_date)
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                } else if ($request->finish_date) {
                                    $query->whereDate('date', $request->finish_date)
                                        ->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                            $query->whereHas('customer', function ($query) use ($request) {
                                                $query->where('customer_id', $request->customer_id);
                                            });
                                        });
                                }
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    } else {
                        $recap = LetterWay::where(function ($query) use ($request) {
                            $query->whereHas('order', function ($query) use ($request) {
                                $query->whereHas('orderCustomerDetail', function ($query) use ($request) {
                                    $query->whereHas('customer', function ($query) use ($request) {
                                        $query->where('customer_id', $request->customer_id);
                                    });
                                });
                            });
                            if ($request->hasInvoice) {
                                if ($request->hasInvoice === "no") {
                                    $query->whereDoesntHave('invoiceDetail', function ($query) {
                                    });
                                } else {
                                    $query->whereHas('invoiceDetail', function ($query) {
                                    });
                                }
                            }
                        })->orderBy('order_id')
                            ->get();
                    }
                } else {
                    $recap = LetterWay::where(function ($query) use ($request) {
                        $query->whereHas('order', function ($query) use ($request) {
                            if ($request->start_date && $request->finish_date) {
                                $query->whereBetween('date', [$request->start_date, $request->finish_date]);
                            } else if ($request->start_date) {
                                $query->whereDate('date', $request->start_date);
                            } else if ($request->finish_date) {
                                $query->whereDate('date', $request->finish_date);
                            }
                        });
                        if ($request->hasInvoice) {
                            if ($request->hasInvoice === "no") {
                                $query->whereDoesntHave('invoiceDetail', function ($query) {
                                });
                            } else {
                                $query->whereHas('invoiceDetail', function ($query) {
                                });
                            }
                        }
                    })->orderBy('order_id')
                        ->get();
                }
            } else {
                $recap = LetterWay::all();
            }

            $data = [
                'data'         => $recap,
                'title'        => 'Recap Laporan',
            ];

            if ($request->vendor_id) {
                $vendorName = Vendor::where('id', $request->vendor_id)->first()->name;
                return (new RecapVendorExport($data))->download('recap-vendor-' . $vendorName . '.xlsx');
            } else {
                return (new RecapVendorExport($data))->download('recap-vendor.xlsx');
            }
        }
    }
}
