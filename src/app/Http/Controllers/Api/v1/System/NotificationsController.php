<?php

namespace App\Http\Controllers\Api\v1\System;

use App\Http\Controllers\Controller;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Support\Facades\Blade;

class NotificationsController extends Controller
{
    public function notifications()
    {
        [$notifications, $unreadNotifications] = $this->getDataSource();
        return ResponseObject::responseSuccess(
            $notifications,
            ["unread" => $unreadNotifications],
            "Get data notifications successfully!"
        );
    }
    public function notificationsRender()
    {
        [$notifications, $unreadNotifications] = $this->getDataSource();
        $str = '<div id="allNotifications" class="p-2">
            <x-renderer.notification.all-notifications :dataSource=\'$notifications\' /> 
        </div>
        <div id="unreadNotifications" class="p-2 hidden">
            <x-renderer.notification.all-notifications :dataSource=\'$unreadNotifications\'/> 
        </div>';
        $html = Blade::render($str, ['notifications' => $notifications, 'unreadNotifications' => $unreadNotifications]);
        return ResponseObject::responseSuccess(
            $html,
            [],
            "Get data notifications successfully!"
        );
    }
    private function getDataSource()
    {
        $notifications = auth()->user()->notifications;
        $unreadNotifications = auth()->user()->unreadNotifications;
        return [$notifications, $unreadNotifications];
    }
}
