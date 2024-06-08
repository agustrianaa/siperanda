<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiNote extends Model
{
    use HasFactory;

    protected $table = 'note_revisi';

    protected $fillable = [
        'detail_rencana_id',
        'note',
    ];

    public function detailRencana(){
        return $this->belongsTo(DetailRencana::class, 'detail_rencana_id');
    }
}
