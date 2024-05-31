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
        $schema->blueprintResolver(fn ($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create('act_travel_expense_claims', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            // $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->unsignedBigInteger('advance_req_id')->nullable();
            $table->decimal('advance_amount', 20, 6)->nullable();
            $table->unsignedBigInteger('currency1_id')->nullable();
            $table->unsignedBigInteger('currency_pair1_id')->nullable();
            $table->decimal('rate_exchange_advance', 20, 6)->nullable();
            $table->decimal('total_advance_amount', 20, 6)->nullable();
            $table->unsignedBigInteger('travel_req_id')->nullable();
            $table->decimal('travel_amount', 20, 6)->nullable();
            $table->unsignedBigInteger('currency2_id')->nullable();
            $table->unsignedBigInteger('currency_pair2_id')->nullable();
            $table->decimal('rate_exchange_travel', 20, 6)->nullable();
            $table->decimal('total_travel_amount', 20, 6)->nullable();
            $table->unsignedBigInteger('rate_exchange_month_id')->nullable();
            $table->unsignedBigInteger('counter_currency_id')->nullable();
            $table->decimal('total_amount_ee', 20, 6)->nullable();
            $table->decimal('total_amount_re', 20, 6)->nullable();
            $table->text('remark')->nullable();
            $table->text('reimbursement_in_words')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
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
        Schema::dropIfExists('act_travel_expense_claims');
    }
};
