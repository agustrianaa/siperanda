<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    // use HasFactory;
    protected $table = 'unit';

    protected $fillable = [
        'name',
        'user_id',
    ];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function rencana(){
        return $this->hasMany(Rencana::class);
    }
}
