<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lainnya extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'lainnyas';
    protected $fillable = ['kode_barang' ,'nama_barang', 'merk', 'type', 'tgl_peroleh', 'asal_usul', 'cara_peroleh', 'jumlah_brg', 'harga_perunit', 'total_harga'];
    protected $dates = ['deleted_at'];
}
