<?php

namespace App\Utils\AccessLogger;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntityNameClickCount
{
    function entityNameClickCountExpensive($owner_id)
    {
        if (!env('ACCESS_LOGGER_ENABLED')) return [];
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        $data = DB::connection($connection)
            ->select("SELECT * 
            FROM view_logger_access_entity_name 
            WHERE 
                owner_id ='$owner_id'
                UNION ALL
                SELECT * 
            FROM view_logger_access_route_name 
            WHERE 
                owner_id ='$owner_id'
            ");
        // Log::info($data);
        if (!app()->isLocal()) {
            $allClick = 0;
            foreach ($data as $line) $allClick += $line->click_count;
            // dump($allClick);
            if ($allClick < 100) return []; //<< Incase too little click, data will not be reliable
        }
        return $data;
    }

    static $singleton = [];

    function __invoke($owner_id)
    {
        if (!isset(static::$singleton[$owner_id])) {
            static::$singleton[$owner_id] = $this->entityNameClickCountExpensive($owner_id);
        }
        return static::$singleton[$owner_id];
    }
}
