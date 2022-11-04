<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCustomerDetail extends Model
{
    use HasFactory;

    protected $table      = 'order_customer_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'order_id',
        'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }


}
