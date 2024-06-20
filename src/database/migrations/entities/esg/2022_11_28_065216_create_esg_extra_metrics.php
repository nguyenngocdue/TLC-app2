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

        $schema->create('esg_extra_metrics', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->date('metric_month')->nullable();
            $table->unsignedBigInteger('workplace_id')->nullable();

            // $table->unsignedInteger("trained_employees")->nullable();
            $table->unsignedInteger("internal_discriminations")->nullable();
            $table->unsignedInteger("internal_grievances")->nullable();
            $table->unsignedInteger("external_grievances")->nullable();
            $table->unsignedInteger("onsite_workers_contractors")->nullable();
            $table->unsignedInteger("number_of_part_time_female")->nullable();
            $table->unsignedInteger("number_of_part_time_male")->nullable();
            $table->float("working_hours_of_part_time")->nullable();

            $table->hasStatus();
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
        Schema::dropIfExists('esg_extra_metrics');
    }
};
