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

        $schema->create('rp_listen_reducers', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('column_name')->nullable();
            $table->string('triggers')->nullable();
            $table->string('listen_to_fields')->nullable();
            $table->string('listen_to_attrs')->nullable();
            $table->string('columns_to_set')->nullable();
            $table->string('attrs_to_compare')->nullable();

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
        Schema::dropIfExists('rp_listen_reducers');
    }
};
