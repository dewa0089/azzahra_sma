<?php

namespace App\Http\Controllers;

use App\Models\Historie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HistorieController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $filter = $request->input('filter_period');

        $query = Historie::where('user_id', $userId);

        if ($filter == 'daily') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($filter == 'monthly') {
            $query->whereYear('created_at', now()->year)
                  ->whereMonth('created_at', now()->month);
        } elseif ($filter == 'yearly') {
            $query->whereYear('created_at', now()->year);
        }

        $histories = $query->orderBy('created_at', 'desc')
                           ->paginate(10)
                           ->appends(['filter_period' => $filter]); // agar filter tetap di url saat pagination

        return view('history.index', compact('histories'));
    }
}
