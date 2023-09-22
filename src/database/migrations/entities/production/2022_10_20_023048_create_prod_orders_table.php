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

        $schema->create('prod_orders', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('production_name');
            $table->string('compliance_name')->nullable();
            $table->string('erp_name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->unsignedBigInteger('sub_project_id');
            $table->unsignedBigInteger('prod_routing_id')->nullable();
            $table->unsignedBigInteger('room_type_id')->nullable();
            $table->string('meta_type')->nullable();
            $table->unsignedBigInteger('meta_id')->nullable();

            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->double('total_hours')->nullable();
            $table->double('total_man_hours')->nullable();

            $table->appendCommonFields();
        });
        // Schema::create('prod_orders', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('slug')->unique();
        //     $table->string('status')->nullable();
        //     $table->string('production_name');
        //     $table->string('compliance_name')->nullable();
        //     $table->text('description')->nullable();
        //     $table->unsignedBigInteger('quantity')->nullable();
        //     $table->unsignedBigInteger('sub_project_id');
        //     $table->unsignedBigInteger('prod_routing_id')->nullable();
        //     $table->string('meta_type')->nullable();
        //     $table->unsignedBigInteger('meta_id')->nullable();
        //     $table->dateTime('started_at')->nullable();
        //     $table->unsignedBigInteger('owner_id');
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
        Schema::dropIfExists('prod_orders');
    }
};
