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
            $table->string('name');
            $table->dateTime('datetime1')->nullable();
            $table->time('datetime2')->nullable();
            $table->date('datetime3')->nullable();
            $table->date('datetime4')->nullable();
            $table->date('datetime5')->nullable();
            $table->date('datetime6')->nullable();
            $table->date('datetime7')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
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
