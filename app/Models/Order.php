<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'orders';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'user_id',
        'vendor_id',
        'unit_id',
        'code',
        'reference',
        'weight',
        'qty',
        'date',
        'deadline',
        'tolerance',
        'status'
    ];

    public static function generateCode()
    {
        /* $query = Order::selectRaw('RIGHT(code, 4) as code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->limit(1)
            ->withTrashed()
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        $month = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return 'DIGI/OM/' . $month[date('n') - 1] . '/' . date('Y') . '/' . sprintf('%04s', $code); */
		
		$query = Order::selectRaw('RIGHT(code, 10) as code')
            ->orderByRaw('RIGHT(code, 10) DESC')
            ->limit(1)
            ->withTrashed()
            ->get();

        if($query->count() > 0 && explode('/',$query[0]->code)[0] == date('y') && explode('/',$query[0]->code)[1] == date('m')) {
			$code = (int)explode('/',$query[0]->code)[2] + 1;
        } else {
            $code = '0001';
        }
		
		return 'DIGI/XPD/DO/' . date('y') . '/' . date('m') . '/' . sprintf('%04s', $code);
    }

    public function status()
    {
        switch($this->status) {
            case '1':
                $status = 'Ready';
                break;
            case '2':
                $status = 'Running';
                break;
            case '3':
                $status = 'Finish';
                break;
            case '4':
                $status = 'Cancel';
                break;
            default:
                $status = 'Tidak Valid';
                break;
        }

        return $status;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function customer()
    {
        return $this->hasMany('App\Models\Customer')->withTrashed();
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor')->withTrashed();
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit')->withTrashed();
    }

    public function orderDestination()
    {
        return $this->hasMany('App\Models\OrderDestination');
    }

    public function orderTransport()
    {
        return $this->hasMany('App\Models\OrderTransport');
    }

    public function letterWay()
    {
        return $this->hasMany('App\Models\LetterWay');
    }

    public function orderCustomerDetail()
    {
        return $this->hasmany('App\Models\OrderCustomerDetail');
    }

}
