<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileManager extends Model {

    use HasFactory;

    protected $table      = 'file_managers';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'name',
        'extension',
        'size
        ',
        'file'
    ];

    public function file()
    {
        if(Storage::exists($this->file)) {
            return asset(Storage::url($this->file));
        } else {
            return asset('website/empty.png');
        }
    }

    public function formatSize()
    {
        if($this->size >= 1073741824) {
            $format = number_format($this->size / 1073741824, 2) . ' GB';
        } else if ($this->size >= 1048576) {
            $format = number_format($this->size / 1048576, 2) . ' MB';
        } else if ($this->size >= 1024) {
            $format = number_format($this->size / 1024, 2) . ' KB';
        } else if ($this->size > 1) {
            $format = $this->size . ' bytes';
        } else if ($this->size == 1) {
            $format = $this->size . ' byte';
        } else {
            $format = '0 bytes';
        }

        return $format;
    }

}
