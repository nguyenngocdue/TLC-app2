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

        $schema->create('hse_extra_metrics', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->nullable(); //unique();
            $table->date('metric_month')->nullable();
            $table->unsignedBigInteger('workplace_id')->nullable();
            $table->float('total_work_hours')->nullable();
            $table->float('total_meeting_toolbox')->nullable();
            $table->float('total_discipline')->nullable();
            $table->float('total_third_party_insp_audit')->nullable();
            $table->float('total_drill')->nullable();
            $table->appendCommonFields();

            $table->unique(['metric_month', 'workplace_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hse_extra_metrics');
    }
};
