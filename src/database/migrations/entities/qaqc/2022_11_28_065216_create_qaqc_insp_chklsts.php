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

        $schema->create('qaqc_insp_chklsts', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->float('progress')->nullable();
            $table->unsignedBigInteger('prod_order_id');
            $table->unsignedBigInteger('prod_routing_id');
            $table->string('consent_number')->nullable();
            $table->unsignedBigInteger('qaqc_insp_tmpl_id');
            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->appendCommonFields();

            $table->unique(['prod_order_id', 'qaqc_insp_tmpl_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qaqc_insp_chklsts');
    }
};
