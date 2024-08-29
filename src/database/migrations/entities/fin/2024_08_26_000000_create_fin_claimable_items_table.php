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

        $schema->create('fin_expense_claim_lines', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('expense_location_id')->nullable();
            $table->unsignedBigInteger('expense_item_id')->nullable();
            $table->unsignedBigInteger('gl_account_id')->nullable();

            $table->date('invoice_date')->nullable();
            $table->string('invoice_no')->nullable();

            $table->double('quantity')->nullable();
            $table->double('unit_price')->nullable();

            $table->unsignedBigInteger('document_currency')->nullable();
            $table->double('total_amount_0')->nullable();

            $table->unsignedBigInteger('vat_product_posting_group_id')->nullable();
            $table->unsignedInteger('vat_product_posting_group_value')->nullable();
            $table->double('total_amount_1')->nullable();

            $table->unsignedBigInteger('rate_exchange')->nullable();
            $table->double('total_amount_lcy')->nullable();

            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_address')->nullable();

            //Parent sheet
            $table->string('claimable_type')->nullable();
            $table->unsignedBigInteger('claimable_id')->nullable();

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
        Schema::dropIfExists('fin_expense_claim_lines');
    }
};
