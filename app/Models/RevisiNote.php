<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiNote extends Model
{
    use HasFactory;

    protected $table = 'note_revisi';

    protected $fillable = [
        'rencana_id',
        'note',
    ];

    public function rencana(){
        return $this->belongsTo(Rencana::class, 'rencana_id');
    }
}
