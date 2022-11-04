<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepaymentDetail extends Model {

    use HasFactory;

    protected $table      = 'repayment_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'repayment_id',
        'letter_way_id',
        'price',
        'total'
    ];

    public function letterWay()
    {
        return $this->belongsTo('App\Models\LetterWay');
    }

}
