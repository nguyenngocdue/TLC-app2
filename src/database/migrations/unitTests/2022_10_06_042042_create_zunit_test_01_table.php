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

        $schema->create('zunit_test_01s', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('text1')->nullable();
            $table->text('text2')->nullable();
            $table->text('text3')->nullable();
            $table->json('text4')->nullable();
            $table->unsignedBigInteger('dropdown1')->nullable();
            $table->unsignedBigInteger('radio1')->nullable();
            $table->boolean('boolean1')->nullable();
            $table->text('signature')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->orderable();
            $table->appendCommonFields();
        });
        // Schema::create('zunit_test_01s', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->nullable();
        //     $table->text('text1')->nullable();
        //     $table->text('text2')->nullable();
        //     $table->text('text3')->nullable();
        //     $table->json('text4')->nullable();
        //     $table->unsignedBigInteger('dropdown1')->nullable();
        //     $table->unsignedBigInteger('radio1')->nullable();
        //     $table->boolean('boolean1')->nullable();
        //     $table->text('signature')->nullable();
        //     // $table->foreign('dropdown1')->references('id')->on('workplaces')->onDelete('cascade');
        //     // $table->foreign('radio1')->references('id')->on('workplaces')->onDelete('cascade');

        //     $table->unsignedBigInteger('parent_id')->nullable();
        //     $table->unsignedInteger('order_no')->nullable();

        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zunit_test_01s');
    }
};
