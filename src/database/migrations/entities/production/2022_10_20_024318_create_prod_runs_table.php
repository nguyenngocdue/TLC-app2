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

        $schema->create('prod_runs', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('prod_sequence_id');
            $table->date('date');
            $table->time('start');
            $table->time('end')->nullable();
            $table->double('worker_number')->nullable();
            $table->double('total_hours')->nullable();
            $table->double('total_man_hours')->nullable();
            $table->appendCommonFields();
        });
        // Schema::create('prod_runs', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('prod_sequence_id');
        //     $table->date('date');
        //     $table->time('start');
        //     $table->time('end')->nullable();
        //     $table->double('worker_number')->nullable();
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
        Schema::dropIfExists('prod_runs');
    }
};
