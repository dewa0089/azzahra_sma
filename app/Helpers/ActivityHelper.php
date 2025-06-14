<?php

namespace App\Helpers;

use App\Models\Historie;
use Illuminate\Support\Facades\Auth;

class ActivityHelper
{
    public static function log($action, $description = null)
    {
        if (Auth::check()) {
            Historie::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'description' => $description,
            ]);
        }
    }
}
