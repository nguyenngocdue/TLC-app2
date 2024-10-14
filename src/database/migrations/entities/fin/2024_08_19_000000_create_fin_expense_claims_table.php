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

        $schema->create('fin_expense_claims', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('rate_exchange_month_id')->nullable();
            $table->unsignedBigInteger("counter_currency_id")->nullable();

            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("employee_id")->nullable();
            $table->unsignedBigInteger("user_discipline_id")->nullable();
            $table->unsignedBigInteger("receiving_method_id")->nullable();

            $table->unsignedBigInteger("travel_from_place_id")->nullable();
            $table->unsignedBigInteger("travel_to_place_id")->nullable();
            $table->unsignedBigInteger("travel_place_pair_id")->nullable();
            $table->double("travel_allowance_per_day")->nullable();

            $table->date("travel_from_date")->nullable();
            $table->date("travel_to_date")->nullable();
            $table->double("travel_day_count")->nullable();
            $table->double("hr_adjusted_date_count_0")->nullable();
            $table->double("hr_adjusted_date_count_1")->nullable();

            $table->unsignedBigInteger("travel_currency_id");
            $table->unsignedBigInteger("currency_pair_id");
            $table->double("travel_rate_exchange");
            $table->double("travel_allowance_amount");

            $table->double("advance_total")->nullable();
            $table->double("travel_allowance_total")->nullable();
            $table->double("expense_total")->nullable();
            $table->double("total_reimbursed")->nullable();
            $table->string("total_reimbursed_in_words_0")->nullable();
            $table->string("total_reimbursed_in_words_1")->nullable();

            $table->unsignedBigInteger("assignee_1")->nullable();
            $table->unsignedBigInteger("assignee_2")->nullable();
            $table->unsignedBigInteger("assignee_3")->nullable();

            $table->hasStatus();
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
        Schema::dropIfExists('fin_expense_claims');
    }
};
