<?php


namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Timer;
use Illuminate\Support\Facades\DB;

class AccessLoggerController extends Controller
{
    function __invoke()
    {
        if (!env('ACCESS_LOGGER_ENABLED')) return;
        $cuId = CurrentUser::id();
        if (is_null($cuId)) return; //<<User has not logged in yet
        $routeName = CurrentRoute::getName();
        $entityName = CurrentRoute::getTypeSingular();
        $entityId = CurrentRoute::getEntityId($entityName);
        $took = Timer::getTimeElapse();
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        DB::connection($connection)->table('logger_access')->insert([
            'owner_id' => $cuId,
            'took' => $took,
            'route_name' => $routeName,
            'url' => url()->current(),
            'env' => env('APP_ENV'),
            'entity_name' => $entityName,
            'entity_id' => $entityId,
        ]);
        // if (env('SHOW_ACCESS_LOGGER')) {
        //     echo " - CU: $cuId";
        //     echo " - Elapse: $took";
        //     echo " - RouteName: $routeName";
        //     echo " - Entity: $entityName";
        //     echo " - Id: $entityId";
        // }
    }
}
