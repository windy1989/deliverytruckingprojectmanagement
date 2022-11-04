<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'drivers';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'city_id',
        'vendor_id',
        'photo',
        'name',
        'photo_identity_card',
        'no_identity_card',
        'photo_driving_licence',
        'no_driving_licence',
        'type_driving_licence',
        'valid_driving_licence',
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

    public function photoIdentityCard()
    {
        if(Storage::exists($this->photo_identity_card)) {
            return asset(Storage::url($this->photo_identity_card));
        } else {
            return asset('website/empty.png');
        }
    }

    public function photoDrivingLicence()
    {
        if(Storage::exists($this->photo_driving_licence)) {
            return asset(Storage::url($this->photo_driving_licence));
        } else {
            return asset('website/empty.png');
        }
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City')->withTrashed();
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor')->withTrashed();
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

}
