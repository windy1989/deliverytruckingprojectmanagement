<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'menus';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'name',
        'url',
        'icon',
        'parent_id',
        'order'
    ];

    public function sub()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id', 'id')->withTrashed();
    }

}
