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

        $schema->create('act_travel_req_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('act_travel_req_id')->nullable();
            $table->unsignedBigInteger('from_id')->nullable();
            $table->unsignedBigInteger('to_id')->nullable();
            $table->unsignedBigInteger('travel_place_pair_id')->nullable();
            $table->double('claimable_amount')->nullable();
            // $table->unsignedBigInteger('workplace_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->dateTime('datetime_outbound_1')->nullable();
            $table->dateTime('datetime_outbound_2')->nullable();
            $table->dateTime('datetime_inbound_1')->nullable();
            $table->dateTime('datetime_inbound_2')->nullable();
            $table->float('total_day')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('act_travel_req_lines');
    }
};
