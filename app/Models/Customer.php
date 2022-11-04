<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{

    use HasFactory, SoftDeletes;

    protected $table      = 'customers';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'city_id',
        'code',
        'name',
        'phone',
        'fax',
        'website',
        'address',
        'pic',
        'warning_date_vendor',
        'danger_date_vendor',
        'warning_date_ttbr',
        'danger_date_ttbr',
        'status'
    ];

    public static function generateCode()
    {
        $query = Customer::selectRaw('RIGHT(code, 4) as code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->limit(1)
            ->withTrashed()
            ->get();

        if ($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }


        return 'CUST-' . sprintf('%04s', $code);
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City')->withTrashed();
    }

    public function status()
    {
        switch ($this->status) {
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

    public function customerBill()
    {
        return $this->hasMany('App\Models\CustomerBill');
    }

    public function invoice()
    {
        return $this->hasMany('App\Models\Invoice')->withTrashed();
    }
    public function receipt()
    {
        return $this->hasMany('App\Models\Receipt')->withTrashed();
    }
}
