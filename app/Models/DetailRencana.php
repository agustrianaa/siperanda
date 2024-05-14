<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRencana extends Model
{
    use HasFactory;
    protected $table = 'realisasi';

    protected $fillable = [
        'rencana_id',
        'kode_komponen_id',
        'satuan_id',
        'volume',
        'harga',
        'note',
    ];
}
