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

        $schema->create('act_travel_reqs', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('travel_type_id')->nullable();
            $table->unsignedBigInteger('workplace_id')->nullable();
            $table->unsignedBigInteger('staff_discipline_id')->nullable();
            $table->unsignedBigInteger('staff_workplace_id')->nullable();
            $table->float('total_travel_day')->nullable();
            $table->decimal('total_travel_amount', 20, 6)->nullable();
            $table->text('remark')->nullable();
            // $table->unsignedBigInteger('req_travel_desk_id')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
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
        Schema::dropIfExists('act_travel_reqs');
    }
};
