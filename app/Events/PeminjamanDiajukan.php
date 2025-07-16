<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Peminjaman;

class PeminjamanDiajukan implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('admin.peminjaman');
    }

    public function broadcastAs()
    {
        return 'peminjaman.diajukan';
    }
}
