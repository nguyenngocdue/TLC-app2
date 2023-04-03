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
        $function = "DROP FUNCTION IF EXISTS `add123`;
        CREATE FUNCTION `add123` (value int) RETURNS int 
        DETERMINISTIC
        BEGIN
            return value + 123;
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
        DB::statement('DROP FUNCTION IF EXISTS `add123`');
    }
};
