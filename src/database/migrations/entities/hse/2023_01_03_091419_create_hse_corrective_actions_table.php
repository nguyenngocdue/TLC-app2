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

        $schema->create('hse_corrective_actions', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->nullable(); //->unique(); ??
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->string('correctable_type')->nullable();
            $table->unsignedBigInteger('correctable_id')->nullable();
            $table->unsignedBigInteger('work_area_id')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->unsignedBigInteger('unsafe_action_type_id')->nullable();
            $table->dateTime('opened_at')->nullable();
            $table->text('remark')->nullable();
            $table->hasDueDate();
            $table->orderable();
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
        Schema::dropIfExists('hse_corrective_actions');
    }
};
