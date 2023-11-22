<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;

class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
    * Send the given notification.
    *
    * @param mixed $notifiable
    * @param \Illuminate\Notifications\Notification $notification
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function send($notifiable, Notification $notification)
    {
        return $notifiable->routeNotificationFor('database')->create([
            'id'      => $notification->id,
            'type'    => get_class($notification),
            'data'    => $this->getData($notifiable, $notification),
            'sender_id'=> $notification->sender_id ?? null,
            'object_type'=> $notification->object_type ?? null,
            'object_id'=> $notification->object_id ?? null,
            'group_name' => $notification->group_name ?? null,
            'read_at' => null,
        ]);
    }
}