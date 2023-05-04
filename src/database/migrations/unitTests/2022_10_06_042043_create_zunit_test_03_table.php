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
        // Schema::create('zunit_test_03s', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->nullable();
        //     $table->dateTime('datetime1')->nullable();
        //     $table->time('datetime2')->nullable();
        //     $table->date('datetime3')->nullable();
        //     $table->date('datetime4')->nullable();
        //     $table->date('datetime5')->nullable();
        //     $table->date('datetime6')->nullable();
        //     $table->date('datetime7')->nullable();

        //     $table->unsignedBigInteger('parent_id')->nullable();
        //     $table->unsignedInteger('order_no')->nullable();
        //     $table->unsignedBigInteger('owner_id');
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

        //     $table->softDeletes();
        // });
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
