<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeKomponen extends Model
{
    use HasFactory;
    protected $table = 'kode_komponen';

    protected $fillable = [
        'kategori_id',
        'kode',
        'kode_parent',
        'uraian',
    ];

    public function detailRencana()
    {
        return $this->hasMany(DetailRencana::class, 'kode_komponen_id');
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function parent()
    {
        return $this->belongsTo(KodeKomponen::class, 'kode_parent', 'kode');
    }

    public function children()
    {
        return $this->hasMany(KodeKomponen::class, 'kode_parent', 'kode');
    }
}
