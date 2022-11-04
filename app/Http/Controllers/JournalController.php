<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller {
    
    public function index(Request $request)
    {
        $journal = Journal::where(function($query) use ($request) {
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
                } else {
                    $query->whereMonth('created_at', date('m'));
                }
            })
            ->oldest()
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        $data = [
            'title'       => 'Digitrans - Jurnal',
            'journal'     => $journal,
            'start_date'  => $request->start_date,
            'finish_date' => $request->finish_date,
            'content'     => 'accounting.journal'
        ];
    
        return view('errors.404', ['data' => $data]);
    }

}
