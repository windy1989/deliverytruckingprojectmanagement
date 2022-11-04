<?php

namespace App\Http\Controllers;

use App\Helper\Digitrans;
use App\Models\Coa;
use Illuminate\Http\Request;

class ProfitLossController extends Controller {
    
    public function index(Request $request)
    {
        $year = $request->year ? $request->year : date('Y');
        $coa_profit = Coa::where('parent_id', '!=', 0)
            ->whereIn('code', ['11001', '11002', '11003', '13001', '13002', '13003'])
            ->whereExists(function($query) use ($request) {
                $query->select('created_at')
                    ->from('journals')
                    ->where(function($query) use ($request) {
                        if($request->start_date && $request->finish_date) {
                            $query->whereBetween('created_at', [$request->start_date, $request->finish_date]);
                        } else if($request->start_date) {
                            $query->whereDate('created_at', $request->start_date);
                        } else if($request->finish_date) {
                            $query->whereDate('created_at', $request->finish_date);
                        }
                    });
            })
            ->where('status', 1)
            ->get();

        $coa_loss = Coa::where('parent_id', '!=', 0)
            ->whereIn('code', ['6001', '6002', '6003', '10001', '10002', '10003', '10004', '10005', '12001', '12002', '12003', '12004', '12005', '12006', '12007', '12008', '12009', '12010', '12011', '12012', '12013', '12014', '14001', '14002', '14003'])
            ->whereExists(function($query) use ($request) {
                $query->select('created_at')
                    ->from('journals')
                    ->where(function($query) use ($request) {
                        if($request->start_date && $request->finish_date) {
                            $query->whereBetween('created_at', [$request->start_date, $request->finish_date]);
                        } else if($request->start_date) {
                            $query->whereDate('created_at', $request->start_date);
                        } else if($request->finish_date) {
                            $query->whereDate('created_at', $request->finish_date);
                        }
                    });
            })
            ->where('status', 1)
            ->get();

        $data = [
            'title'       => 'Digitrans - Laba Rugi',
            'coa_profit'  => $coa_profit,
            'coa_loss'    => $coa_loss,
            'total_profit_loss' => Digitrans::totalProfitLossYearly($year),
            'start_date'  => $request->start_date,
            'year'		  => $year,
            'finish_date' => $request->finish_date,
            'content'     => 'accounting.profit_loss'
        ];
    
        return view('layouts.index', ['data' => $data]);
    }

}
