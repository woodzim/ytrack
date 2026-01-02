<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RiwayatPerjalananCreated implements ShouldBroadcast, ShouldQueue
{
    public function __construct(
        public int $sesiAlatId,
        public float $lat,
        public float $lng,
        public string $waktu,
        public ?int $speed,
        public string $alt,
        public string $sat
        
    ) {}

    public function broadcastOn()
    {
        return new Channel('tracking.sesi.' . $this->sesiAlatId);
    }
    public function broadcastAs()
    {
        return 'LokasiBaru';
    }
}

