<?php

namespace App\Models;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coa extends Model
{

    use HasFactory, SoftDeletes;

    protected $table      = 'coas';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'code',
        'name',
        'parent_id',
        'description',
        'status'
    ];

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

    public function parent()
    {
        if ($this->parent_id == 0) {
            $parent = 'Parent';
        } else {
            $parent = Coa::find($this->parent_id)->withTrashed()->name;
        }

        return $parent;
    }

    public function sub()
    {
        return $this->hasMany('App\Models\Coa', 'parent_id')->withTrashed();
    }

    public function balance($param, $identifier = null, $parent = false)
    {
        $code    = [];
        $sub_coa = Coa::where('parent_id', $identifier)->withTrashed()->get();

        foreach ($sub_coa as $sc) {
            $code[] = $sc['code'];
        }

        if ($param == 'debit') {
            if ($parent) {
                $query = Journal::select('nominal')->whereIn('coa_debit', $code)->sum('nominal');
            } else {
                $query = Journal::select('nominal')->where('coa_debit', $identifier)->sum('nominal');
            }
        } else if ($param == 'credit') {
            if ($parent) {
                $query = Journal::select('nominal')->whereIn('coa_credit', $code)->sum('nominal');
            } else {
                $query = Journal::select('nominal')->where('coa_credit', $identifier)->sum('nominal');
            }
        } else {
            $query = 0;
        }

        return $query;
    }

    public function getChild($param, $identifier = null, $parent = false)
    {
        $code    = [];
        $sub_coa = Coa::where('parent_id', $identifier)->withTrashed()->get();

        foreach ($sub_coa as $sc) {
            $code[] = $sc['code'];
        }

        if ($param == 'debit') {
            if ($parent) {
                $query = Journal::select('description', 'nominal')->whereIn('coa_debit', $code)->get();
            } else {
                $query = Journal::select('description', 'nominal')->where('coa_debit', $identifier)->get();
            }
        } else if ($param == 'credit') {
            if ($parent) {
                $query = Journal::select('description', 'nominal')->whereIn('coa_credit', $code)->get();
            } else {
                $query = Journal::select('description', 'nominal')->where('coa_credit', $identifier)->get();
            }
        } else {
            $query = 0;
        }

        return $query;
    }
}
