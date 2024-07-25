<?php

namespace App\Utils\AccessLogger;

use Illuminate\Support\Facades\DB;

class LoggerAccessRecent
{
    function loggerAccessRecentDocument($owner_id)
    {
        if (!env('ACCESS_LOGGER_ENABLED')) return [];
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        $data = DB::connection($connection)
            ->select("SELECT DISTINCT url, entity_name,route_name,entity_id, MAX(created_at) AS created_at
                -- FROM logger_access
                FROM (
                    SELECT * 
                    FROM logger_access 
                    WHERE owner_id = '$owner_id'
                    ORDER BY id DESC 
                    LIMIT 100
                ) AS XXX
                GROUP BY url, entity_name, route_name, entity_id
                -- ORDER BY created_at DESC
                LIMIT 10");
        return $data;
    }

    static $singleton = [];

    function __invoke($owner_id)
    {
        if (!isset(static::$singleton[$owner_id])) {
            static::$singleton[$owner_id] = $this->loggerAccessRecentDocument($owner_id);
        }
        return static::$singleton[$owner_id];
    }
}
