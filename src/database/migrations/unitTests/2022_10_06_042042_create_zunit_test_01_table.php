<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('zunit_test_01s', function (Blueprint $table) {
            $table->id();
            $table->text('text1')->nullable();
            $table->text('text2')->nullable();
            $table->text('text3')->nullable();
            $table->json('text4')->nullable();
            $table->unsignedBigInteger('dropdown1')->nullable();
            $table->unsignedBigInteger('radio1')->nullable();
            $table->unsignedBigInteger('boolean1')->nullable();
            $table->foreign('dropdown1')->references('id')->on('workplaces')->onDelete('cascade');
            $table->foreign('radio1')->references('id')->on('workplaces')->onDelete('cascade');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
        });
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
