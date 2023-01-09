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
        DB::statement("CREATE OR REPLACE VIEW views_full_prod_sequence AS
        (
            SELECT the_date 
            FROM view_mon2sat_2023_2033 v
            WHERE 1=1
                AND the_date >= DATE_ADD('2023-02-01 00:00:00', INTERVAL FLOOR(8/8) DAY)
                AND v.the_date NOT IN (
                        SELECT ph_date 
                        FROM public_holidays ph 
                        WHERE ph.workplace_id=2
                    )
            ORDER BY the_date
            LIMIT 1
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS views_full_prod_sequence');
    }
};
