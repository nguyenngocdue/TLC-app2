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
        Schema::create('zunit_test_9s', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->string('department_1')->nullable();
            $table->string('department_2')->nullable();
            $table->string('category_id')->nullable();
            $table->string('sub_project_1')->nullable();
            $table->string('user_1')->nullable();
            $table->string('user_2')->nullable();
            $table->string('user_3')->nullable();
            $table->string('prod_order_1')->nullable();
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
        Schema::dropIfExists('zunit_test_9s');
    }
};
