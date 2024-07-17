<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggaran';

    protected $fillable = [
        'all_anggaran',
        'unit_id',
        'anggaran_perunit',
        'tahun',
    ];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function getTahunAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['tahun'])->format('Y');
    }
}
