<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class NotificationController extends Controller
{
    public function saveToken(Request $request)
    {
        $user = Auth::user();
        $user->fcm_token = $request->token;
        $user->save();

        return response()->json(['token saved.']);
    }

    // Fungsi untuk mengirim notifikasi ke user tertentu
    public function sendNotification(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user || !$user->fcm_token) {
            return response()->json(['error' => 'User atau token tidak ditemukan.'], 404);
        }

        $SERVER_API_KEY = env('FCM_SERVER_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $SERVER_API_KEY,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $user->fcm_token,
            'notification' => [
                'title' => $request->title,
                'body' => $request->body,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi dikirim.',
            'fcm_response' => $response->json()
        ]);
    }
}
