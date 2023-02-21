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
        Schema::create('zunit_test_09s', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('department_2')->nullable();
            $table->unsignedBigInteger('user_2')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('user_3')->nullable();

            $table->unsignedBigInteger('project_1')->nullable();
            $table->unsignedBigInteger('sub_project_1')->nullable();
            $table->unsignedBigInteger('prod_routing_1')->nullable();
            $table->unsignedBigInteger('prod_order_1')->nullable();

            $table->unsignedBigInteger('prod_discipline_1')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();

            $table->unsignedBigInteger('department_1')->nullable();
            $table->unsignedBigInteger('user_1')->nullable();
            $table->unsignedBigInteger('user_4')->nullable();
            $table->string('user_position_1')->nullable();

            $table->unsignedBigInteger('priority_id')->nullable();
            $table->dateTime('due_date')->nullable();

            $table->integer('total')->nullable();
            $table->integer('accepted')->nullable();
            $table->integer('rejected')->nullable();
            $table->integer('remaining')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('order_no')->nullable();

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
        Schema::dropIfExists('zunit_test_09s');
    }
};
