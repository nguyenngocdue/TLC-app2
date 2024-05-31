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

        $schema->create('eco_labor_impacts', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable(); //Detailed Actions
            $table->text('description')->nullable(); //Remark
            $table->float('head_count')->nullable();
            $table->float('man_day')->nullable();
            $table->float('labor_cost')->nullable();
            $table->float('total_cost')->nullable();
            $table->unsignedBigInteger('eco_sheet_id');
            $table->unsignedBigInteger('department_id')->nullable();
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
        Schema::dropIfExists('eco_labor_impacts');
    }
};
