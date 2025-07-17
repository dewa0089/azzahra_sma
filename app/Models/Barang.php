<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'barangs';

    protected $fillable = [
        'kode_barang', 'nama_barang', 'jumlah_barang',
        'jumlah_rusak', 'jumlah_hilang', 'tgl_peroleh',
        'harga_perunit', 'total_harga'
    ];

    protected $dates = ['deleted_at'];

    // Tambahkan relasi ke peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'barang_id');
    }

    // Jika ada relasi ke pengembalian juga
    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class, 'barang_id');
    }
}
