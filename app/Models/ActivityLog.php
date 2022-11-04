<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {

    use HasFactory;

    protected $table      = 'activity_logs';
    protected $primaryKey = 'id';

    public function getDataByDate($param)
    {
        $query = ActivityLog::whereDay('created_at', $param)->where('causer_id', $this->causer_id)->get();
        return $query;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'causer_id', 'id')->withTrashed();
    }

}
