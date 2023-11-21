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

        $schema->create('qaqc_insp_chklst_shts', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('qaqc_insp_chklst_id');
            $table->unsignedBigInteger('qaqc_insp_tmpl_sht_id');
            $table->unsignedBigInteger('prod_discipline_id');
            $table->float('progress')->nullable(); //version 2
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->unsignedBigInteger('assignee_2')->nullable();
            $table->orderable();
            $table->appendCommonFields();

            $table->unique(['qaqc_insp_chklst_id', 'qaqc_insp_tmpl_sht_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qaqc_insp_chklst_sht');
    }
};
