<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Route;


class NotificationsController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }
    private function getDataSource()
    {
        return CurrentUser::get()->notifications;
    }

    public function index()
    {
        return view("notifications.notifications-index", [
            'dataSource' => $this->getDataSource(),
        ]);
    }
    public function markAsRead($type, $id, $idNotification)
    {
        $typeEntity = (new ($type))->getTable();
        $routeName = "{$typeEntity}.edit";
        $routeExits =  (Route::has($routeName));
        $theNotification = CurrentUser::get()
            ->notifications
            ->when($idNotification, function ($query) use ($idNotification) {
                return $query->where('id', $idNotification);
            });
        // dump($theNotification);
        $theNotification->markAsRead();
        $scrollTo = $theNotification->first()->scroll_to;
        $href = $routeExits ? route($routeName, $id) . "#" . $scrollTo : "#";

        return redirect($href);
    }
}
