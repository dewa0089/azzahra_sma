<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Rusak extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'rusaks';
    protected $fillable = ['jenis_brg_rusak','jumlah_brg_rusak', 'gambar_brg_rusak', 'tgl_rusak','keterangan', 'status', 'elektronik_id', 'lainnya_id', 'mobiler_id'];

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


}
