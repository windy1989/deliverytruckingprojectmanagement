<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimDetail extends Model {

    use HasFactory;

    protected $table      = 'claim_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'claim_id',
        'letter_way_id',
        'percentage',
        'rupiah',
        'tolerance'
    ];

    public function claim()
    {
        return $this->belongsTo('App\Models\Claim');
    }

    public function letterWay()
    {
        return $this->belongsTo('App\Models\LetterWay');
    }

}
