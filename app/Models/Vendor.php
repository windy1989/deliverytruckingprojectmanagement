<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'vendors';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'code',
        'name',
        'status'
    ];

    public static function generateCode($name)
    {
        $getName = substr($name,0,1);
        $query   = Vendor::select('code')->withTrashed()->max('code');

        if($query) {
            $codename = substr($query,5,1);
            if($getName===$codename)
            {
                $code = '0001';
            }
            else
            {
                $code = ((int)substr($query, 6, 4)) + 1;
            }
        } else {
            $code = '0001';
        }

        return 'VEND-' . $getName . sprintf('%04s', $code);
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

    public function destination()
    {
        return $this->hasMany('App\Models\Destination')->withTrashed();
    }

}
