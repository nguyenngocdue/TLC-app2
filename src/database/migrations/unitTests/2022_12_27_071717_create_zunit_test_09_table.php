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
        $schema->blueprintResolver(fn($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('zunit_test_09s', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            //Reduce 11
            $table->unsignedBigInteger('department_2')->nullable();
            $table->unsignedBigInteger('user_2')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('user_3')->nullable();

            // $table->date('ot_date_1')->nullable();
            // $table->float('remaining_hours')->nullable();
            //Reduce 1111
            $table->unsignedBigInteger('project_1')->nullable();
            $table->unsignedBigInteger('sub_project_1')->nullable();
            $table->unsignedBigInteger('prod_routing_1')->nullable();
            $table->unsignedBigInteger('prod_order_1')->nullable();

            //Reduce (A,A1)=>B1,(A,A2=>B2)
            $table->unsignedBigInteger('currency1_id')->nullable();
            $table->unsignedBigInteger('currency_pair1_id')->nullable();
            $table->unsignedBigInteger('currency2_id')->nullable();
            $table->unsignedBigInteger('currency_pair2_id')->nullable();
            $table->unsignedBigInteger('counter_currency_id')->nullable();
            $table->unsignedBigInteger('rate_exchange_month_id')->nullable();
            $table->float('rate_exchange_value_1')->nullable();
            $table->float('rate_exchange_value_2')->nullable();

            //Assign
            $table->unsignedBigInteger('prod_discipline_1')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            //Dot
            $table->unsignedBigInteger('department_1')->nullable();
            $table->unsignedBigInteger('user_1')->nullable();
            $table->unsignedBigInteger('user_4')->nullable();
            $table->string('user_position_1')->nullable();
            //Date Offset
            $table->unsignedBigInteger('priority_id')->nullable();
            // $table->dateTime('due_date')->nullable();
            $table->hasDueDate();
            //Expression
            $table->integer('total')->nullable();
            $table->integer('accepted')->nullable();
            $table->integer('rejected')->nullable();
            $table->integer('remaining')->nullable();
            //Expression Date Time
            $table->dateTime('datetime_1')->nullable();
            $table->dateTime('datetime_2')->nullable();
            $table->bigInteger('datetime_3')->nullable();
            $table->date('date_1')->nullable();
            $table->date('date_2')->nullable();
            $table->bigInteger('date_3')->nullable();
            $table->time('time_1')->nullable();
            $table->time('time_2')->nullable();
            $table->bigInteger('time_3')->nullable();

            $table->float('number')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();

            $table->string('in_words_0')->nullable();
            $table->string('in_words_1')->nullable();

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
        Schema::dropIfExists('zunit_test_09s');
    }
};
