<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historie extends Model
{
    protected $fillable = ['user_id', 'action', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

