<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'perbaikans';
    protected $fillable = ['jumlah_perbaikan', 'tanggal_perbaikan', 'biaya_perbaikan' ,'keterangan', 'rusak_id'];

    public function rusak()
    {
        return $this->belongsTo(Rusak::class, 'rusak_id');
    }
}
