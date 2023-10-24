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
        $schema->blueprintResolver(function ($table, $callback) {
            return new BlueprintExtended($table, $callback);
        });

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
            $table->string('slug')->unique();
            $table->appendCommonFields();
        });

        // Schema::create('workplaces', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->unsignedBigInteger('def_assignee')->nullable();
        //     $table->float('standard_working_hour')->nullable();
        //     $table->string('slug')->unique();
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        // });
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
