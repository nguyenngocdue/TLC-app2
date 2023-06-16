<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!env('ACCESS_LOGGER_ENABLED')) return;
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        DB::connection($connection)->statement("CREATE OR REPLACE VIEW view_logger_access_route_name AS
        
        SELECT owner_id, route_name, count(*) click_count FROM `logger_access`
        WHERE 1=1
            AND created_at >= now()-interval 3 month
        GROUP BY owner_id, route_name
        ORDER BY owner_id, click_count DESC
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!env('ACCESS_LOGGER_ENABLED')) return;
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        DB::connection($connection)->statement('DROP VIEW IF EXISTS view_logger_access_route_name');
    }
};
