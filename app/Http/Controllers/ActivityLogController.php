<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller {

    public function index(Request $request)
    {
        $activity = ActivityLog::selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_at')
            ->where('causer_id', session('id'))
            ->where(function($query) use ($request) {
                if($request->start_date && $request->finish_date) {
                    $query->whereBetween('created_at', [$request->start_date, $request->finish_date]);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }
            })
            ->distinct(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'))
            ->paginate(7);

        $data = [
            'title'       => 'Digitrans - Aktivitas',
            'activity'    => $activity,
            'start_date'  => $request->start_date,
            'finish_date' => $request->finish_date,
            'content'     => 'activity_log'
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
