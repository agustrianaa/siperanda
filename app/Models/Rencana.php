<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rencana extends Model
{
    use HasFactory;
    protected $table = 'rencana';

    protected $fillable = [
        'unit_id',
        'tahun',
        'jumlah',
        'anggaran',
        'status',
    ];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function getTahunAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['tahun'])->format('Y');
    }
}
