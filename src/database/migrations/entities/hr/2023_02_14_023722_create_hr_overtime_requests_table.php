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

        $schema->create('hr_overtime_requests', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text("description")->nullable();
            $table->double("total_hours")->nullable();
            $table->unsignedBigInteger("workplace_id");
            $table->unsignedBigInteger('assignee_1');
            $table->unsignedBigInteger('user_team_ot_id')->nullable();
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
        Schema::dropIfExists('hr_overtime_requests');
    }
};
