<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationPrice extends Model {

    use HasFactory;

    protected $table      = 'destination_prices';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'destination_id',
        'unit_id',
        'date',
        'price_vendor',
        'price_customer'
    ];

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

}
