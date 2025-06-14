<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pemusnaan extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'pemusnaans';
    protected $fillable = ['jumlah_pemusnaan', 'tanggal_pemusnaan', 'gambar_pemusnaan' ,'keterangan', 'rusak_id'];

    public function rusak()
    {
        return $this->belongsTo(Rusak::class, 'rusak_id');
    }
}
