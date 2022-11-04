<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'warehouses';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'code',
        'name',
        'address'
    ];

}
