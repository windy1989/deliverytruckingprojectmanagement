<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $table      = 'photos';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'photo_type',
        'photo_id',
        'name',
        'path',
        'date',
    ];

    public function photoable()
    {
        return $this->morphTo();
    }
}
