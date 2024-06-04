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

        $schema->create('zunit_test_03s', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->dateTime('datetime1')->nullable();
            $table->time('datetime2')->nullable();
            $table->date('datetime3')->nullable();
            $table->date('datetime4')->nullable();
            $table->date('datetime5')->nullable();
            $table->date('datetime6')->nullable();
            $table->date('datetime7')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();
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
        Schema::dropIfExists('zunit_test_03s');
    }
};
