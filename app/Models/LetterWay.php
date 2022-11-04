<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LetterWay extends Model {

    use HasFactory;

    protected $table      = 'letter_ways';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'order_id',
        'destination_id',
        'number',
        'weight',
        'qty',
        'received_date',
        'send_back_attachment',
        'legalize_attachment',
        'legalize_received_date',
        'legalize_send_back_attachment',
        'legalize_send_back_received_date',
        'ttbr_qty',
        'ttbr_attachment',
        'ttbr_received_date',
        'status'
    ];

    public function sendBackAttachment()
    {
        if(Storage::exists($this->send_back_attachment)) {
            return asset(Storage::url($this->send_back_attachment));
        } else {
            return asset('website/empty.png');
        }
    }

    public function legalizeAttachment()
    {
        if(Storage::exists($this->legalize_attachment)) {
            return asset(Storage::url($this->legalize_attachment));
        } else {
            return asset('website/empty.png');
        }
    }

    public function legalizeSendBackAttachment()
    {
        if(Storage::exists($this->legalize_send_back_attachment)) {
            return asset(Storage::url($this->legalize_send_back_attachment));
        } else {
            return asset('website/empty.png');
        }
    }

    public function ttbrAttachment()
    {
        if(Storage::exists($this->ttbr_attachment)) {
            return asset(Storage::url($this->ttbr_attachment));
        } else {
            return asset('website/empty.png');
        }
    }

    public function status()
    {
        switch($this->status) {
            case '1':
                $status = 'Diproses';
                break;
            case '2':
                $status = 'Finish';
                break;
            default:
                $status = 'Tidak Valid';
                break;
        }

        return $status;
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order')->withTrashed();
    }

    public function destination()
    {
        return $this->belongsTo('App\Models\Destination')->withTrashed();
    }

    public function invoiceDetail()
    {
        return $this->hasOne('App\Models\InvoiceDetail');
    }

    public function claimDetail()
    {
        return $this->hasOne('App\Models\ClaimDetail');
    }

    public function orderCustomerDetail()
    {
        return $this->hasMany('App\Models\OrderCustomerDetail');
    }

}
