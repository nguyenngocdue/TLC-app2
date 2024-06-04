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

        $schema->create('qaqc_wirs', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedInteger('doc_id');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('ncr_status_unique_value')->nullable();
            $table->string('ncr_all_closed')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->unsignedBigInteger('prod_routing_id')->nullable();
            $table->unsignedBigInteger('prod_order_id')->nullable();
            $table->unsignedBigInteger('prod_discipline_id')->nullable();
            $table->unsignedBigInteger('wir_description_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            // $table->dateTime('due_date')->nullable();
            // $table->unsignedInteger('qc_total')->nullable();
            // $table->unsignedInteger('qc_accepted')->nullable();
            // $table->unsignedInteger('qc_remaining')->nullable();
            // $table->unsignedInteger('qc_rejected')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->hasDueDate();
            $table->closable();
            $table->appendCommonFields();

            $table->unique(['prod_order_id', 'wir_description_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qaqc_wirs');
    }
};
