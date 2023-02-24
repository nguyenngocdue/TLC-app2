<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


class NotificationsController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }
    private function getDataSource()
    {
        return auth()->user()->notifications->toArray();
    }

    public function index()
    {
        return view("notifications.notifications-index", [
            'dataSource' => $this->getDataSource(),
        ]);
    }
    public function markAsRead($type, $id, $idNotification)
    {
        $typePlural = Str::plural($type);
        $routeName = "{$typePlural}.edit";
        $routeExits =  (Route::has($routeName));
        $href =  $routeExits ? route($routeName, $id) : "#";
        auth()->user()
            ->unreadNotifications
            ->when($idNotification, function ($query) use ($idNotification) {
                return $query->where('id', $idNotification);
            })
            ->markAsRead();
        return redirect($href);
    }
}
