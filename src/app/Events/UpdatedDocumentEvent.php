<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatedDocumentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $previousValue;
    public $currentValue;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($previousValue, $currentValue)
    {
        $this->previousValue = $previousValue;
        $this->currentValue = $currentValue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
