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
        DB::connection($connection)->statement("CREATE VIEW view_logger_access_entity_id AS
        
        SELECT entity_name, entity_id, count(*) click_count
            FROM `logger_access` 
            WHERE 1=1
                -- AND entity_name='project'
                AND entity_id IS NOT NULL
                AND created_at >= now()-interval 3 month

            GROUP BY entity_name, entity_id
            ORDER BY entity_name, click_count DESC, entity_id DESC
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
        DB::connection($connection)->statement('DROP VIEW IF EXISTS view_logger_access_entity_id');
    }
};
