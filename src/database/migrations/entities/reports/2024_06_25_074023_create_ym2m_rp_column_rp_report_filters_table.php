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

        $schema->create('ym2m_rp_column_rp_report_filters', function (BlueprintExtended $table) {
            $table->id();

            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('column_id');
            $table->unsignedInteger('col_span');
            $table->string('bw_list_ids');
            $table->unsignedBigInteger('black_or_white');
            $table->boolean('is_required');
            $table->string('default_value');
            $table->boolean('has_listen_to');
            $table->boolean('allow_clear');
            $table->boolean('is_multiple');
            $table->unsignedBigInteger('control_type');

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
        Schema::dropIfExists('ym2m_rp_column_rp_report_filters');
    }
};
