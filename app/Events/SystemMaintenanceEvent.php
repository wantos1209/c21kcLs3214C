<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemMaintenanceEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $connection = 'sync';
    public string $time;

    public function __construct($time)
    {
        $this->time = $time;
        // \Log::info('Event ahhh: ' . $this->time);
    }
    public function broadcastOn(): array
    {
        return [
            new Channel('system-maintenance'),
        ];
    }

    public function broadcastAs()
    {
        return 'asdasd';
    }

}
