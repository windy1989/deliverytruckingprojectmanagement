<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDestination extends Model {

    use HasFactory;

    protected $table      = 'order_destinations';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'order_id',
        'destination_id'
    ];

    public function destination()
    {
        return $this->belongsTo('App\Models\Destination')->withTrashed();
    }

}
