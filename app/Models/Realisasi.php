<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    use HasFactory;
    protected $table = 'realisasi';

    protected $fillable = [
        'rencana_id',
        'skedul',
        'realisasi'
    ];
}
