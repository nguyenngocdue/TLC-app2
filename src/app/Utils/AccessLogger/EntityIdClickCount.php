<?php

namespace App\Utils\AccessLogger;

use Illuminate\Support\Facades\DB;

class EntityIdClickCount
{
    function __invoke($entityName)
    {
        if (!env('ACCESS_LOGGER_ENABLED')) return [];
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        $data = DB::connection($connection)
            ->select("SELECT * 
            FROM view_logger_access_entity_id 
            WHERE 
                entity_name ='$entityName'
            ");
        if (!app()->isLocal()) {
            $allClick = 0;
            foreach ($data as $line) $allClick += $line->click_count;
            // dump($allClick);
            if ($allClick < 100) return []; //<< Incase too little click, data will not be reliable
        }
        return $data;
    }
}
