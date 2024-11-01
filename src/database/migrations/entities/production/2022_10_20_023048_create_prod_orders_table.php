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

        $schema->create('prod_orders', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('production_name');
            $table->string('compliance_name')->nullable();
            $table->string('erp_name')->nullable();
            $table->string('product_type_on_chklst')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->float('prod_sequence_progress')->nullable();

            $table->unsignedBigInteger('sub_project_id');
            $table->unsignedBigInteger('prod_routing_id')->nullable();
            $table->unsignedBigInteger('room_type_id')->nullable();
            $table->string('meta_type')->nullable();
            $table->unsignedBigInteger('meta_id')->nullable();

            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->double('total_hours')->nullable();
            $table->double('total_man_hours')->nullable();

            $table->unsignedInteger('sheet_count')->nullable();
            $table->unsignedInteger('total_calendar_days')->nullable();
            $table->unsignedInteger('no_of_sundays')->nullable();
            $table->unsignedInteger('no_of_ph_days')->nullable();
            $table->unsignedInteger('total_days_no_sun_no_ph')->nullable();
            $table->unsignedInteger('total_days_have_ts')->nullable();
            $table->integer('total_discrepancy_days')->nullable();

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
        Schema::dropIfExists('prod_orders');
    }
};
