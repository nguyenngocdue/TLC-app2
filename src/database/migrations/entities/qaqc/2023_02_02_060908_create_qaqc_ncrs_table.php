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

        $schema->create('qaqc_ncrs', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedInteger('doc_id')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('parent_type')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->unsignedBigInteger('prod_routing_id')->nullable();
            $table->unsignedBigInteger('prod_order_id')->nullable();
            $table->unsignedBigInteger('prod_discipline_id')->nullable();
            $table->unsignedBigInteger('prod_discipline_1_id')->nullable();
            $table->unsignedBigInteger('prod_discipline_2_id')->nullable();
            $table->unsignedBigInteger('user_team_id')->nullable();
            $table->unsignedBigInteger('inter_subcon_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            // $table->dateTime('due_date')->nullable();
            $table->unsignedBigInteger('defect_root_cause_id')->nullable();
            $table->unsignedBigInteger('defect_disposition_id')->nullable();
            $table->unsignedBigInteger('defect_severity')->nullable();
            $table->unsignedBigInteger('defect_report_type')->nullable();
            $table->float('qty_man_power')->nullable();
            $table->float('hour_per_man')->nullable();
            $table->float('total_hour')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->unsignedBigInteger('assignee_2')->nullable();
            $table->hasDueDate();
            $table->closable();
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
        Schema::dropIfExists('qaqc_ncrs');
    }
};
