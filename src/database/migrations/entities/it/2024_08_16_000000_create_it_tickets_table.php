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

        $schema->create('it_tickets', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text("trace")->nullable();
            $table->text("description")->nullable();
            $table->unsignedBigInteger("it_ticket_cat_id")->nullable();
            $table->unsignedBigInteger("it_ticket_sub_cat_id")->nullable();
            $table->unsignedBigInteger("priority_id")->nullable();
            $table->dateTime("due_date")->nullable();
            $table->unsignedBigInteger("assignee_1")->nullable();

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
        Schema::dropIfExists("it_tickets");
    }
};
