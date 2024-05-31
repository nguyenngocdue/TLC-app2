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
        $schema->blueprintResolver(fn ($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('hr_timesheet_workers', function (BlueprintExtended $table) {
            $table->id();
            $table->date("ts_date");
            $table->unsignedBigInteger("team_id")->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->double("total_hours", 8, 2)->nullable();

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
        Schema::dropIfExists('hr_timesheet_workers');
    }
};
