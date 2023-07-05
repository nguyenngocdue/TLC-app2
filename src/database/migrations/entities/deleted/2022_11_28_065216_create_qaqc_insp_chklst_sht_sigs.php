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

        $schema->create('qaqc_insp_chklst_sht_sigs', function (BlueprintExtended $table) {
            $table->id();
            $table->text('value');
            $table->unsignedBigInteger('qaqc_insp_chklst_sht_id');
            $table->appendCommonFields();
        });
        // Schema::create('qaqc_insp_chklst_sht_sigs', function (Blueprint $table) {
        //     $table->id();
        //     $table->text('value');
        //     $table->unsignedBigInteger('owner_id');
        //     $table->unsignedBigInteger('qaqc_insp_chklst_sht_id');
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
        Schema::dropIfExists('qaqc_insp_chklst_sht');
    }
};
