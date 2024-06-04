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

        $schema->create('zunit_test_08s', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();


            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->unsignedBigInteger('assignee_2')->nullable();

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
        Schema::dropIfExists('zunit_test_08s');
    }
};
