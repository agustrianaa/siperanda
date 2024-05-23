<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    use HasFactory;
    protected $table = 'realisasi';

    protected $fillable = [
        'detail_rencana_id',
        'skedul',
        'realisasi'
    ];

    public function detailRencana(){
        return $this->belongsTo(DetailRencana::class, 'detail_rencana_id');
    }
}
