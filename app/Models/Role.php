<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'roles';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'name'
    ];

    public function roleAccess()
    {
        return $this->hasMany('App\Models\RoleAccess');
    }

}
