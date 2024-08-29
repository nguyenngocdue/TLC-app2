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

        $schema->create('fin_expense_items', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('name_vi')->nullable();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('gl_account_id')->nullable();
            $table->unsignedBigInteger('debit_group_id')->nullable();

            $table->unsignedBigInteger('expense_type_id')->nullable();
            $table->unsignedBigInteger('expense_location_id')->nullable(); // Domestic, International

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
        Schema::dropIfExists('fin_expense_items');
    }
};
