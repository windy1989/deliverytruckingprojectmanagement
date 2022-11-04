<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model {

    use HasFactory;

    protected $table      = 'journals';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'coa_debit',
        'coa_credit',
        'nominal',
        'description'
    ];

    public function detail($param = null)
    {
        if($param) {
            $query = Journal::whereDate('created_at', $this->created_at)->groupBy($param)->get();
        } else {
            $query = Journal::whereDate('created_at', $this->created_at)->get();
        }

        return $query;
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
