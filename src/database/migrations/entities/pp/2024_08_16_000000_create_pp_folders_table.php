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

        $schema->create('pp_folders', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text("description")->nullable();

            $table->string("parent_type")->nullable();
            $table->unsignedBigInteger("parent_id")->nullable();

            $table->boolean("opened")->default(0);
            $table->boolean("draggable")->default(0);
            $table->boolean("droppable")->default(0);

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
        Schema::dropIfExists("pp_folders");
    }
};
