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
        $function = "
        DROP FUNCTION IF EXISTS `get_hr_year_from_date`;
        CREATE FUNCTION `get_hr_year_from_date` (value date) RETURNS text 
        DETERMINISTIC
        BEGIN
            return if(value BETWEEN concat(year(value),'-12-26') AND concat(year(value),'-12-31'), year(value)+1, year(value));
        END;
        ";

        DB::unprepared($function);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP FUNCTION IF EXISTS `get_hr_year_from_date`');
    }
};
