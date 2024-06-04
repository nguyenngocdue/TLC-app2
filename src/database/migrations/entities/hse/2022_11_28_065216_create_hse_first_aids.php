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

        $schema->create('hse_first_aids', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('employeeid')->nullable();
            $table->string('slug')->nullable(); //unique();
            $table->unsignedBigInteger('injured_person_id')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->unsignedBigInteger('work_area_id')->nullable();
            $table->dateTime('injury_datetime')->nullable();
            $table->text('nature_of_injury')->nullable();
            $table->text('treatment_provided')->nullable();
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
        Schema::dropIfExists('hse_first_aids');
    }
};
