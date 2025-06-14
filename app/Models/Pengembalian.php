<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'pengembalians';
    protected $fillable = ['jumlah_brg_baik', 'jumlah_brg_rusak','jumlah_brg_hilang', 'tgl_pengembalian', 'status', 'peminjaman_id'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
}
