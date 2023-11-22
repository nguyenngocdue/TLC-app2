<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;

class DatabaseChannel extends IlluminateDatabaseChannel
{
    public function send($notifiable, $notification)
    {
        $data = $this->getData($notifiable, $notification);
        [
            'sender_id' => $sender_id,
            'object_type' => $object_type,
            'object_id' => $object_id,
            'group_name' => $group_name,
            'message' => $message,
        ] = $data;
        unset($data['sender_id']);
        unset($data['object_type']);
        unset($data['object_id']);
        unset($data['group_name']);
        return $notifiable->routeNotificationFor('database')->create([
            'id'      => $notification->id,
            'type'    => get_class($notification),
            'data'    => $data,
            'sender_id' => $sender_id ?? null,
            'object_type' => $object_type ?? null,
            'object_id' => $object_id ?? null,
            'group_name' => $group_name ?? null,
            'read_at' => null,
        ]);
    }
}
