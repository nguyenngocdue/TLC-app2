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
        $function = "DROP FUNCTION IF EXISTS `get_hr_month_from_date`;
        CREATE FUNCTION `get_hr_month_from_date` (value date) RETURNS text 
        DETERMINISTIC
        BEGIN
            return if(day(value) BETWEEN 1 AND 25, substr(value, 1,7), substr(DATE_ADD(value, INTERVAL 1 MONTH),1,7));
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
        DB::statement('DROP FUNCTION IF EXISTS `get_hr_month_from_date`');
    }
};
