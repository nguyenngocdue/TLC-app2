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

        $schema->create('site_daily_assignment_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('site_daily_assignment_id')->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("sub_project_id")->nullable();

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
        Schema::dropIfExists('site_daily_assignment_lines');
    }
};
