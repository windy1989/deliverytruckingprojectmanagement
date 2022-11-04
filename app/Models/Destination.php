<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destination extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'destinations';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'vendor_id',
        'label',
        'city_origin',
        'city_destination'
    ];

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor')->withTrashed();
    }

    public function destinationPrice()
    {
        return $this->hasMany('App\Models\DestinationPrice');
    }

    public function cityOrigin()
    {
        return $this->belongsTo('App\Models\City', 'city_origin', 'id')->withTrashed();
    }

    public function cityDestination()
    {
        return $this->belongsTo('App\Models\City', 'city_destination', 'id')->withTrashed();
    }

}
