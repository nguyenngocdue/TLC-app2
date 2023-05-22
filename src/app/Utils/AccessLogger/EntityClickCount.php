<?php

namespace App\Utils\AccessLogger;

use Illuminate\Support\Facades\DB;

class EntityClickCount
{
    function __invoke($entityName)
    {
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        $data = DB::connection($connection)
            ->select("SELECT * 
            FROM view_logger_access_entity 
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
