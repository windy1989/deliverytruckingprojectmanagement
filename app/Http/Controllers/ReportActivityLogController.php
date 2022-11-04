<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportActivityLogController extends Controller {

    public function index(Request $request)
    {
        $activity = ActivityLog::selectRaw('causer_id, DATE_FORMAT(created_at, "%d-%m-%Y") as created_at')
            ->where(function($query) use ($request) {
                if($request->start_date && $request->finish_date) {
                    $query->whereBetween('created_at', [$request->start_date, $request->finish_date]);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }

                if($request->user_id) {
                    $query->where('causer_id', $request->user_id);
                }
            })
            ->distinct(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'))
            ->paginate(3);

        $data = [
            'title'       => 'Digitrans - Laporan Aktivitas',
            'activity'    => $activity,
            'start_date'  => $request->start_date,
            'finish_date' => $request->finish_date,
            'user_id'     => $request->user_id,
            'user'        => User::all(),
            'content'     => 'report.activity_log'
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
