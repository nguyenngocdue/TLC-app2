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

        $schema->create('esg_master_sheets', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->date('esg_month');
            $table->unsignedBigInteger('workplace_id');
            $table->double("total")->nullable();
            $table->unsignedBigInteger('esg_tmpl_id')->nullable();
            $table->appendCommonFields();

            $table->unique(['esg_month', 'esg_tmpl_id', 'workplace_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esg_master_sheets');
    }
};
