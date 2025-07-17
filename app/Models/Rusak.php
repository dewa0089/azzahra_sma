<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rusak extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'rusaks';
    protected $fillable = ['jenis_brg_rusak','jumlah_brg_rusak', 'gambar_brg_rusak', 'tgl_rusak','keterangan', 'status', 'elektronik_id', 'lainnya_id', 'mobiler_id'];
    protected $dates = ['deleted_at'];
    public function elektronik()
    {
        return $this->belongsTo(Elektronik::class, 'elektronik_id');
    }

    public function lainnya()
    {
        return $this->belongsTo(Lainnya::class, 'lainnya_id');
    }

    public function mobiler()
    {
        return $this->belongsTo(Mobiler::class, 'mobiler_id');
    }

    public function pemusnahan()
{
    return $this->hasOne(Pemusnaan::class, 'rusak_id');
}

public function perbaikan()
{
    return $this->hasOne(Perbaikan::class, 'rusak_id');
}



}
