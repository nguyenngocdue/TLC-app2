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
        DB::statement("CREATE OR REPLACE VIEW view_otr_remainings AS
        (
            SELECT 
                'idless' AS id,
                -- 'nameless' AS name,
                month(ot_date) AS month, 
                user_id, employeeid, 
                60 AS allowed_hours,
                sum(total_time) AS total_hours,
                60 - sum(total_time) AS remaining_hours
            FROM `hr_overtime_request_lines`
            GROUP BY month, user_id, employeeid, allowed_hours
            ORDER BY month, user_id
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_otr_remainings');
    }
};
