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

        $schema->create('hr_onboarding_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->float('onboarding_hours')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('employeeid')->nullable();
            $table->string("position_rendered")->nullable();
            $table->string("remark")->nullable();
            $table->unsignedBigInteger('hr_onboarding_id');
            $table->unsignedBigInteger('onboarding_course_id');
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
        Schema::dropIfExists('hr_onboarding_lines');
    }
};
