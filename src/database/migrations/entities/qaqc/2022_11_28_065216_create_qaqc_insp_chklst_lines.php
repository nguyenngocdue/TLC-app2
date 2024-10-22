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

        $schema->create('qaqc_insp_chklst_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('control_type_id');
            $table->text('value')->nullable();
            $table->text('value_on_hold')->nullable();
            $table->unsignedBigInteger('qaqc_insp_chklst_sht_id');
            $table->unsignedBigInteger('qaqc_insp_group_id');
            $table->unsignedBigInteger('qaqc_insp_control_value_id')->nullable();
            $table->unsignedBigInteger('qaqc_insp_control_group_id');
            $table->unsignedInteger('col_span')->nullable()->default(12);
            $table->unsignedInteger('checkpoint_level')->nullable()->default(12);

            $table->unsignedBigInteger('inspector_id')->nullable();

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
        Schema::dropIfExists('qaqc_insp_chklst_lines');
    }
};
