<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RPD extends Model
{
    use HasFactory;
    protected $table = 'rpd';

    protected $fillable = [
        'detail_rencana_id',
        'bulan_rpd',
        'jumlah_rpd',
    ];

    public function detailRencana(){
        return $this->belongsTo(DetailRencana::class, 'detail_rencana_id');
    }
}
