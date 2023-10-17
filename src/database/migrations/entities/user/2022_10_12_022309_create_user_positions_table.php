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

        $schema->create('user_positions', function (BlueprintExtended $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            // $table->string('slug')->unique();

            $table->unsignedBigInteger("position_prefix")->nullable();
            $table->unsignedBigInteger("position_1")->nullable();
            $table->unsignedBigInteger("position_2")->nullable();
            $table->unsignedBigInteger("position_3")->nullable();

            $table->text("report_to")->nullable();
            $table->string("job_desc")->nullable();
            $table->string("job_requirement")->nullable();
            $table->text("report_to_1")->nullable();
            $table->string("job_desc_1")->nullable();
            $table->string("job_requirement_1")->nullable();

            $table->unsignedBigInteger("assignee_1")->nullable();

            $table->appendCommonFields();

            $table->unique(["position_prefix", "position_1", "position_2", "position_3"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_position1s');
    }
};
