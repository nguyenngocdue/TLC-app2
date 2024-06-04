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

        $schema->create('hse_insp_chklst_shts', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('hse_insp_tmpl_sht_id');
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->unsignedBigInteger('workplace_id')->nullable();
            $table->float('progress')->nullable(); //version 2

            $table->date('start_date')->nullable();

            // $table->dateTime('start_time')->nullable();
            // $table->dateTime('finish_time')->nullable();
            $table->orderable();
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
        Schema::dropIfExists('hse_insp_chklst_shts');
    }
};
