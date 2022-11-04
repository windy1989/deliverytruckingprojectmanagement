<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transport extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'transports';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'no_plate',
        'brand',
        'valid_kir',
        'photo_stnk',
        'valid_stnk',
        'type',
        'status'
    ];

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

    public function photoStnk()
    {
        if(Storage::exists($this->photo_stnk)) {
            return asset(Storage::url($this->photo_stnk));
        } else {
            return asset('website/empty.png');
        }
    }

}
