<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggran';

    protected $fillable = [
        'all_anggran',
        'unit_id',
        'anggaran_perunit',
    ];

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
}
