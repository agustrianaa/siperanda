<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    // use HasFactory;
    protected $table = 'super_admin';

    protected $fillable = [
        'name',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
