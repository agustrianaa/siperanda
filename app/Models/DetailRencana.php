<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRencana extends Model
{
    use HasFactory;
    protected $table = 'detail_rencana';

    protected $fillable = [
        'rencana_id',
        'noparent_id',
        'kode_komponen_id',
        'satuan_id',
        'volume',
        'harga',
        'status',
        'uraian',
        'is_revised',
        'is_revised2',
        'is_revised3',
        'revisi_keterangan',
        'created_by',
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
        return $this->belongsTo(Rencana::class);
    }

    public function realisasi()
    {
        return $this->hasMany(Realisasi::class);
    }
}
