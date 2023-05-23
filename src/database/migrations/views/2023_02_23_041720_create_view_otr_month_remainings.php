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
        DB::statement("CREATE OR REPLACE VIEW view_otr_month_remainings AS
        
            SELECT 
                'idless' AS id,
                -- 'nameless' AS name,
                substr(ot_date, 1, 7) AS `year_month0`, 
                user_id,
                40 AS month_allowed_hours,
                round(sum(total_time),2) AS total_hours,
                round(40 - sum(total_time),2) AS month_remaining_hours
            FROM `hr_overtime_request_lines`
            GROUP BY `year_month0`, user_id
            ORDER BY `year_month0`, user_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_otr_month_remainings');
    }
};
