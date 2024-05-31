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

        $schema->create('hse_insp_chklst_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('control_type_id');
            $table->text('value')->nullable();
            $table->unsignedBigInteger('hse_insp_chklst_sht_id');
            $table->unsignedBigInteger('hse_insp_group_id');
            $table->unsignedBigInteger('hse_insp_control_value_id')->nullable();
            $table->unsignedBigInteger('hse_insp_control_group_id');

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
        Schema::dropIfExists('hse_insp_chklst_lines');
    }
};
