<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model {

    use HasFactory;

    protected $table      = 'receipt_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'receipt_id',
        'invoice_id'
    ];

    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

}
