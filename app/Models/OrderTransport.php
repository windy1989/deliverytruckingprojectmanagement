<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderTransport extends Model {

    use HasFactory;

    protected $table      = 'order_transports';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'order_id',
        'driver_id',
        'transport_id',
        'warehouse_origin',
        'warehouse_destination'
    ];

    public function driver()
    {
        return $this->belongsTo('App\Models\Driver')->withTrashed();
    }

    public function transport()
    {
        return $this->belongsTo('App\Models\Transport')->withTrashed();
    }

    public function warehouseOrigin()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_origin', 'id')->withTrashed();
    }

    public function warehouseDestination()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_destination', 'id')->withTrashed();
    }

}
