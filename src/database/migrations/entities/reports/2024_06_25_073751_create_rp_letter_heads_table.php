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

        $schema->create('rp_letter_heads', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('content')->nullable();

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
        Schema::dropIfExists('rp_letter_heads');
    }
};
