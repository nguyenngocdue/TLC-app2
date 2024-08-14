<?php

use App\BigThink\BlueprintExtended;
use Illuminate\Database\Migrations\Migration;
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
        $schema = DB::connection()->getSchemaBuilder();
        $schema->blueprintResolver(fn($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('workplaces', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('def_assignee')->nullable();
            $table->unsignedBigInteger('travel_place_id')->nullable();
            $table->float('standard_working_min')->nullable();
            $table->float('break_duration_in_min')->nullable();
            $table->time('standard_start_time')->nullable();
            $table->time('standard_start_break')->nullable();
            $table->string('weekend_days')->nullable();

            $table->unsignedInteger('remind_timesheet_day')->nullable();
            $table->time('remind_timesheet_time')->nullable();

            $table->string('slug')->unique();
            $table->appendCommonFields();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workplaces');
    }
};
