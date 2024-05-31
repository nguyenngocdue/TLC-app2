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

        $schema->create('act_travel_expense_claim_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('unit_price', 20, 6)->nullable();
            $table->float('quantity')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('counter_currency_id')->nullable();
            $table->unsignedBigInteger('rate_exchange_month_id')->nullable();
            $table->unsignedBigInteger('travel_expense_claim_id')->nullable();
            $table->decimal('total_amount', 20, 6)->nullable();
            $table->unsignedBigInteger('currency_pair_id')->nullable();
            $table->decimal('rate_exchange', 20, 6)->nullable();
            $table->decimal('total_estimated_amount', 20, 6)->nullable();
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
        Schema::dropIfExists('act_travel_expense_claim_lines');
    }
};
