<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashflowController extends Controller {

    public function index(Request $request)
    {
        $coa     = [1002, 1003, 2001, 2002, 2003, 2004];
        $journal = Journal::where(function($query) use ($request) {
                if($request->start_date && $request->finish_date) {
                    $query->whereBetween('created_at', [$request->start_date, $request->finish_date]);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                } else {
                    $query->whereMonth('created_at', date('m'));
                }
            });

        $data = [
            'title'       => 'Digitrans - Arus Kas',
            'journal_in'  => $journal->whereIn('coa_debit', $coa)->oldest()->groupBy(DB::raw('DATE(created_at)'))->get(),
            'journal_out' => $journal->whereIn('coa_credit', $coa)->oldest()->groupBy(DB::raw('DATE(created_at)'))->get(),
            'start_date'  => $request->start_date,
            'finish_date' => $request->finish_date,
            'content'     => 'accounting.cashflow'
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
