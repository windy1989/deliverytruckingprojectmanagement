<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model {

    use HasFactory;

    protected $table      = 'claims';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'claimable_type',
        'claimable_id',
        'date',
        'description',
        'flag'
    ];

    public function claimable()
    {
        return $this->morphTo();
    }

    public function claimDetail()
    {
        return $this->hasMany('App\Models\ClaimDetail');
    }

    public function flag()
    {
        switch($this->flag) {
            case '1':
                $flag = 'Berat';
                break;
            case '2':
                $flag = 'Qty';
                break;
            default:
                $flag = 'Tidak Valid';
                break;
        }

        return $flag;
    }

}
