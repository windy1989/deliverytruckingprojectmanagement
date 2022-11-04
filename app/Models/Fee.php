<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fee extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'fees';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'user_id',
        'coa_debit',
        'coa_credit',
        'attachment',
        'code',
        'description',
        'type',
        'total'
    ];

    public function attachment()
    {
        if(Storage::exists($this->attachment)) {
            return asset(Storage::url($this->attachment));
        } else {
            return asset('website/empty.png');
        }
    }

    public static function generateCode($param)
    {
        $query = Fee::selectRaw('RIGHT(code, 4) as code')
            ->where('type', '=', $param)
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->limit(1)
            ->withTrashed()
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        $type = "";
        if($param=="1")
        {
            $type = "BKK";
        }
        elseif($param=="2")
        {
            $type = "BKM";
        }
        elseif($param=="3")
        {
            $type = "BBM";
        }
        elseif($param=="4")
        {
            $type = "BBK";
        }
        $month = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        return 'DIGI/' . $type . '/' . $month[date('n') - 1] . '/' . date('Y') . '/' . sprintf('%04s', $code);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function type()
    {
        switch($this->type) {
            case '1':
                $type = 'BKK';
                break;
            case '2':
                $type = 'BKM';
                break;
            case '3':
                $type = 'BBM';
                break;
            case '4':
                $type = 'BBK';
                break;
            default:
                $type = 'Tidak Valid';
                break;
        }

        return $type;
    }

    public function coaDebit()
    {
        return $this->belongsTo('App\Models\Coa', 'coa_debit', 'code')->withTrashed();
    }

    public function coaCredit()
    {
        return $this->belongsTo('App\Models\Coa', 'coa_credit', 'code')->withTrashed();
    }

}
