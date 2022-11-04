<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{

    use HasFactory;

    protected $table      = 'invoice_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'invoice_id',
        'letter_way_id',
        'price',
        'total'
    ];
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }
    public function letterWay()
    {
        return $this->belongsTo('App\Models\LetterWay');
    }
}
