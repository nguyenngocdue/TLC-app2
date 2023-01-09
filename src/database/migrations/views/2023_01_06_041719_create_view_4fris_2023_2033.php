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
        DB::statement("CREATE OR REPLACE VIEW view_fris_2023_2033 AS
        (
            WITH recursive Date_Ranges AS ( 
                SELECT 2023 as year, '2023-01-06' AS the_date, WEEKDAY('2023-01-06') weekday 
                UNION ALL 
                SELECT year(the_date) year, the_date + INTERVAL 7 day, WEEKDAY(the_date) weekday 
                FROM Date_Ranges 
                WHERE the_date < '2033-12-31' 
                ) 
            SELECT * FROM Date_Ranges
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_fris_2023_2033');
    }
};
