<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'invoices';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'user_id',
        'customer_id',
        'code',
        'down_payment',
        'tax',
        'discount',
        'subtotal',
        'grandtotal'
    ];

    public static function generateCode()
    {
        $query = Invoice::selectRaw('RIGHT(code, 10) as code')
            ->orderByRaw('RIGHT(code, 10) DESC')
            ->limit(1)
            ->withTrashed()
            ->get();

        if($query->count() > 0 && explode('/',$query[0]->code)[0] == date('y') && explode('/',$query[0]->code)[1] == date('m')) {
            $code = (int)explode('/',$query[0]->code)[2] + 1;
        } else {
            $code = '0001';
        }

        return 'DIGI/XPD/INV/' . date('y') . '/' . date('m') . '/' . sprintf('%04s', $code);
    }

    public static function numberToWord($param)
    {
        $score    = trim(abs((int)$param));
		$alphabet = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'DELAPAN', 'SEMBILAN', 'SEPULUH', 'SEBELAS'];
		$value    = '';

		if($score < 12) {
			$value = ' ' . $alphabet[$score];
		} else if($score < 20) {
			$value = Invoice::numberToWord($score - 10) . ' BELAS';
		} else if($score < 100) {
			$value = Invoice::numberToWord($score / 10) . ' PULUH' . Invoice::numberToWord($score % 10);
		} else if($score < 200) {
			$value = ' SERATUS' . Invoice::numberToWord($score - 100);
		} else if($score < 1000) {
			$value = Invoice::numberToWord($score/100) . ' RATUS' . Invoice::numberToWord($score % 100);
		} else if($score < 2000) {
			$value = ' SERIBU' . Invoice::numberToWord($score - 1000);
		} else if($score < 1000000) {
			$value = Invoice::numberToWord($score / 1000) . ' RIBU' . Invoice::numberToWord($score % 1000);
		} else if($score < 1000000000) {
			$value = Invoice::numberToWord($score / 1000000) . ' JUTA' . Invoice::numberToWord($score % 1000000);
		} else if($score < 1000000000000) {
			$value = Invoice::numberToWord($score / 1000000000) . ' MILYAR' . Invoice::numberToWord(fmod($score, 1000000000));
		} else if($score < 1000000000000000) {
			$value = Invoice::numberToWord($score / 1000000000000) . ' TRILIYUN' . Invoice::numberToWord(fmod($score, 1000000000000));
		}

        if($param < 0) {
            return 'MINUS ' . $value;
        } else {
            return $value;
        }
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function journal()
    {
        return $this->hasOne('App\Models\Journal', 'description', 'code');
    }

    public function invoiceDetail()
    {
        return $this->hasMany('App\Models\InvoiceDetail');
    }

    public function receiptDetail()
    {
        return $this->hasOne('App\Models\ReceiptDetail');
    }

}
