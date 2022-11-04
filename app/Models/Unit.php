<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'units';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'name',
        'type'
    ];

    public function type()
    {
        switch($this->type) {
            case '1':
                $type = 'Satuan Harga Per Tujuan';
                break;
            case '2':
                $type = 'Satuan Qty Order';
                break;
            default:
                $type = 'Tidak Valid';
                break;
        }

        return $type;
    }

}
