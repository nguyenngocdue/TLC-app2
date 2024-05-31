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

        $schema->create('act_advance_reqs', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            // $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->unsignedBigInteger('radio_advance_type')->nullable();
            $table->decimal('advance_amount', 20, 6)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('advance_amount_word')->nullable();
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
        Schema::dropIfExists('act_advance_reqs');
    }
};
