<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeKomponen extends Model
{
    use HasFactory;
    protected $table = 'kode_komponen';

    protected $fillable = [
        'kategori_id',
        'kode',
        'kode_parent',
        'uraian',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
