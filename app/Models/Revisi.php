<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revisi extends Model
{
    use HasFactory;
    protected $table = 'revisi';

    protected $fillable = [
        'rencana_id',
        'noparent_id',
        'kode_komponen_id',
        'satuan_id',
        'volume',
        'harga',
        'total',
        'uraian',
        'status',
        'revision'
    ];

    public function kodeKomponen()
    {
        return $this->belongsTo(KodeKomponen::class, 'kode_komponen_id');
    }

    // Definisikan relasi ke model Satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function rencana()
    {
        return $this->belongsTo(Rencana::class, 'rencana_id');
    }
}
