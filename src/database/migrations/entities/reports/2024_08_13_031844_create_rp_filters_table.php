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
        $schema->blueprintResolver(fn($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('rp_filters', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger('report_id')->nullable();
            $table->string('data_index')->nullable();
            $table->string('entity_type')->nullable();
            $table->string('bw_list_ids')->nullable();
            $table->unsignedBigInteger('black_or_white')->nullable();
            $table->boolean('is_required')->nullable();
            $table->string('default_value')->nullable();
            $table->unsignedBigInteger('listen_reducer_id')->nullable();
            $table->boolean('allow_clear')->nullable();
            $table->boolean('is_multiple')->nullable();
            $table->unsignedBigInteger('control_type')->nullable();
            $table->boolean('is_active')->nullable();

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
        Schema::dropIfExists('rp_filters');
    }
};
