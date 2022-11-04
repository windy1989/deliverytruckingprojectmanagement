<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleAccess extends Model {

    protected $table      = 'role_accesses';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'role_id',
        'menu_id'
    ];

    public function menu()
    {
        return $this->belongsTo('App\Models\Menu')->withTrashed();
    }

}
