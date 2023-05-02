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

        $schema->create('zunit_test_09s', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('department_2')->nullable();
            $table->unsignedBigInteger('user_2')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('user_3')->nullable();
            $table->date('ot_date_1')->nullable();
            $table->float('remaining_hours')->nullable();

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

            $table->dateTime('datetime_1')->nullable();
            $table->dateTime('datetime_2')->nullable();
            $table->bigInteger('datetime_3')->nullable();

            $table->date('date_1')->nullable();
            $table->date('date_2')->nullable();
            $table->bigInteger('date_3')->nullable();

            $table->time('time_1')->nullable();
            $table->time('time_2')->nullable();
            $table->bigInteger('time_3')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->orderable();
            $table->appendCommonFields();
        });
        // Schema::create('zunit_test_09s', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name')->nullable();

        //     $table->unsignedBigInteger('department_2')->nullable();
        //     $table->unsignedBigInteger('user_2')->nullable();
        //     $table->unsignedBigInteger('category_id')->nullable();
        //     $table->unsignedBigInteger('user_3')->nullable();
        //     $table->date('ot_date_1')->nullable();
        //     $table->float('remaining_hours')->nullable();

        //     $table->unsignedBigInteger('project_1')->nullable();
        //     $table->unsignedBigInteger('sub_project_1')->nullable();
        //     $table->unsignedBigInteger('prod_routing_1')->nullable();
        //     $table->unsignedBigInteger('prod_order_1')->nullable();

        //     $table->unsignedBigInteger('prod_discipline_1')->nullable();
        //     $table->unsignedBigInteger('assignee_1')->nullable();

        //     $table->unsignedBigInteger('department_1')->nullable();
        //     $table->unsignedBigInteger('user_1')->nullable();
        //     $table->unsignedBigInteger('user_4')->nullable();
        //     $table->string('user_position_1')->nullable();

        //     $table->unsignedBigInteger('priority_id')->nullable();
        //     $table->dateTime('due_date')->nullable();

        //     $table->integer('total')->nullable();
        //     $table->integer('accepted')->nullable();
        //     $table->integer('rejected')->nullable();
        //     $table->integer('remaining')->nullable();

        //     $table->dateTime('datetime_1')->nullable();
        //     $table->dateTime('datetime_2')->nullable();
        //     $table->bigInteger('datetime_3')->nullable();

        //     $table->date('date_1')->nullable();
        //     $table->date('date_2')->nullable();
        //     $table->bigInteger('date_3')->nullable();

        //     $table->time('time_1')->nullable();
        //     $table->time('time_2')->nullable();
        //     $table->bigInteger('time_3')->nullable();

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
        Schema::dropIfExists('zunit_test_09s');
    }
};
