<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBill extends Model {
   
    use HasFactory;

    protected $table      = 'customer_bills';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'customer_id',
        'bank',
        'bill'
    ];

}
