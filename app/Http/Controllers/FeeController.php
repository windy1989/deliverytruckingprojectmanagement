<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\Fee;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FeeController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Biaya',
            'coa'     => Coa::where('parent_id', 0)->where('status', 1)->get(),
            'content' => 'accounting.fee'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'attachment',
            'created_at',
            'code',
            'user_id',
            'coa_debit',
            'coa_credit',
            'description',
            'total'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Fee::count();

        $query_data = Fee::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('description', 'like', "%$search%")
                            ->orWhereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('coaDebit', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('coaCredit', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $new_start_date = date('Y-m-d 00:00:00', strtotime($request->start_date));

                    $new_end_date = date('Y-m-d 23:59:59', strtotime($request->finish_date));
                    $query->whereBetween('created_at', [$new_start_date, $new_end_date]);
                } else if($request->start_date) {
                    $new_start_date = date('Y-m-d 00:00:00', strtotime($request->start_date));
                    $query->whereDate('created_at', $new_start_date);
                } else if($request->finish_date) {
                    $new_end_date = date('Y-m-d 23:59:59', strtotime($request->finish_date));
                    $query->whereDate('created_at', $new_end_date);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Fee::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('description', 'like', "%$search%")
                            ->orWhereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('coaDebit', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('coaCredit', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }

                if($request->start_date && $request->finish_date) {
                    $new_start_date = date('Y-m-d 00:00:00', strtotime($request->start_date));

                    $new_end_date = date('Y-m-d 23:59:59', strtotime($request->finish_date));
                    $query->whereBetween('created_at', [$new_start_date, $new_end_date]);
                } else if($request->start_date) {
                    $new_start_date = date('Y-m-d 00:00:00', strtotime($request->start_date));
                    $query->whereDate('created_at', $new_start_date);
                } else if($request->finish_date) {
                    $new_end_date = date('Y-m-d 23:59:59', strtotime($request->finish_date));
                    $query->whereDate('created_at', $new_end_date);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $attachment = '<a href="' . $val->attachment() . '" data-lightbox="' . $val->attachment() . '" data-title="' . $val->code . '"><img src="' . $val->attachment() . '" class="img-thumbnail img-fluid" style="max-width:50px; max-height:50px;"></a>';

                $response['data'][] = [
                    $nomor,
                    $attachment,
                    date('d M Y', strtotime($val->created_at)),
                    $val->code,
                    '<a href="' . url('user') . '" class="text-primary">' . $val->user->name . '</a>',
                    $val->coaDebit->name,
                    $val->coaCredit->name,
                    $val->description,
                    'Rp ' . number_format($val->total, 2, ',', '.'),
                    '
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'attachment'  => 'mimes:jpeg,jpg,png',
            'description' => 'required',
            'type'        => 'required',
            'coa_debit'   => 'required',
            'coa_credit'  => 'required',
            'total'       => 'required'
        ], [
            'attachment.mimes'     => 'Foto harus berformat jpeg, jpg, png.',
            'description.required' => 'Mohon mengisi keterangan.',
            'type.required'        => 'Mohon memilih jenis.',
            'coa_debit.required'   => 'Mohon memilih coa debet.',
            'coa_credit.required'  => 'Mohon memilih coa kredit.',
            'total.required'       => 'Mohon mengisi nominal.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Fee::create([
                'user_id'     => session('id'),
                'coa_debit'   => $request->coa_debit,
                'coa_credit'  => $request->coa_credit,
                'attachment'  => $request->has('attachment') ? $request->file('attachment')->store('public/fee/attachment') : null,
                'code'        => Fee::generateCode($request->type),
                'description' => $request->description,
                'type'        => $request->type,
                'total'       => str_replace(',', '', $request->total)
            ]);

            if($query) {
                if($request->type == 1) {
                    Journal::create([
                        'coa_debit'   => $query->coa_debit,
                        'coa_credit'  => $query->coa_credit,
                        'nominal'     => $query->total,
                        'description' => $query->code
                    ]);
                } else {
                    Journal::create([
                        'coa_debit'   => $query->coa_credit,
                        'coa_credit'  => $query->coa_debit,
                        'nominal'     => $query->total,
                        'description' => $query->code
                    ]);
                }

                activity()
                    ->performedOn(new Fee())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data biaya ' . $query->code);

                $response = [
                    'status'  => 200,
                    'message' => 'Data telah diproses.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data gagal diproses.'
                ];
            }
        }

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        $findCode = Fee::where('id', $request->id)->first();
        $delJurnal = Journal::where('description', $findCode->code)->delete();

        $query = Fee::where('id', $request->id)->delete();

        if($query) {
            activity()
                ->performedOn(new Fee())
                ->causedBy(session('id'))
                ->log('Menghapus data biaya');

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

}
