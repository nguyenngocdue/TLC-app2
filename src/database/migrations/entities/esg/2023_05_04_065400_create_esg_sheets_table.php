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

        $schema->create('esg_sheets', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->date('esg_date')->nullable();
            // $table->unsignedBigInteger('workplace_id');
            $table->double("total")->nullable();
            $table->unsignedBigInteger('esg_tmpl_id')->nullable();
            $table->unsignedBigInteger('esg_master_sheet_id')->nullable();

            $table->orderable();
            $table->appendCommonFields();

            // $table->unique(['esg_date', 'esg_tmpl_id']); 
            //<< User can soft delete on form of esg_master_sheet
            //<< And then create another in the correct month
            //<< It will cause duplicate exception
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esg_sheets');
    }
};
