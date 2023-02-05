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
        Schema::create('zunit_test_03s', function (Blueprint $table) {
            $table->id();
            $table->dateTime('datetime1')->nullable();
            $table->dateTime('datetime2')->nullable();
            $table->dateTime('datetime3')->nullable();
            $table->dateTime('datetime4')->nullable();
            $table->dateTime('datetime5')->nullable();
            $table->dateTime('datetime6')->nullable();
            $table->dateTime('datetime7')->nullable();
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
        Schema::dropIfExists('zunit_test_03s');
    }
};
