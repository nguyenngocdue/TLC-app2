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

        $schema->create('esg_tmpls', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('unit')->nullable();
            // $table->unsignedBigInteger('ghg_cat_id')->nullable();
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
        Schema::dropIfExists('esg_tmpls');
    }
};
