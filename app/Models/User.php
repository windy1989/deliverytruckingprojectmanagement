<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use HasFactory, SoftDeletes;

    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $hidden     = ['password'];
    protected $fillable   = [
        'role_id',
        'photo',
        'signature',
        'name',
        'username',
        'password',
        'email',
        'phone',
        'address',
        'status'
    ];

    public function photo()
    {
        if(Storage::exists($this->photo)) {
            return asset(Storage::url($this->photo));
        } else {
            return asset('website/empty.png');
        }
    }

    public function signature()
    {
        if(Storage::exists($this->signature)) {
            return asset(Storage::url($this->signature));
        } else {
            return asset('website/empty.png');
        }
    }

    public function status()
    {
        switch($this->status) {
            case '1':
                $status = 'Aktif';
                break;
            case '2':
                $status = 'Tidak Aktif';
                break;
            default:
                $status = 'Tidak Valid';
                break;
        }

        return $status;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role')->withTrashed();
    }

}
