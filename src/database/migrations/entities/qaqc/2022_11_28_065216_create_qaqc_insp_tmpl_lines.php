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

        $schema->create('qaqc_insp_tmpl_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('control_type_id')->nullable();
            $table->unsignedBigInteger('qaqc_insp_tmpl_sht_id');
            $table->unsignedBigInteger('qaqc_insp_group_id')->nullable();
            $table->unsignedBigInteger('qaqc_insp_control_group_id')->nullable();
            $table->unsignedInteger('col_span')->nullable()->default(12);
            $table->unsignedInteger('checkpoint_level')->nullable()->default(12);

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
        Schema::dropIfExists('qaqc_insp_tmpl_lines');
    }
};
