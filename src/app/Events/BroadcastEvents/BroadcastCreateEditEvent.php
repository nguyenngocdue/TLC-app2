<?php

namespace App\Events\BroadcastEvents;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastCreateEditEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $dataSource;
    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $dataSource, $type)
    {
        $this->user = $user;
        $this->dataSource = $dataSource;
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nameChanel = 'edit.' . $this->type . '-' . $this->dataSource['id'];
        return new Channel($nameChanel);
    }
    public function broadcastAs()
    {
        return 'BroadcastUpdateEvent.' . $this->type . '-' . $this->dataSource['id'];
    }
    public function broadcastWith()
    {
        return [
            'user' => $this->user,
            'dataSource' => $this->dataSource,
            'type' => $this->type,
        ];
    }
}
