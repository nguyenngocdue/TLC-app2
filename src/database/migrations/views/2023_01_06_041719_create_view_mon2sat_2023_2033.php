<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW view_mon2sat_2023_2033 AS
        (
            SELECT * FROM view_mons_2023_2033
            UNION ALL
            SELECT * FROM view_tues_2023_2033
            UNION ALL
            SELECT * FROM view_weds_2023_2033
            UNION ALL
            SELECT * FROM view_thus_2023_2033
            UNION ALL
            SELECT * FROM view_fris_2023_2033
            UNION ALL
            SELECT * FROM view_sats_2023_2033
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_mon2sat_2023_2033');
    }
};
