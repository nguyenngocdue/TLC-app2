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

        $schema->create('ym2m_rp_block_rp_pages', function (BlueprintExtended $table) {
            $table->id();
            $table->unsignedBigInteger("page_id")->nullable();
            $table->unsignedBigInteger("block_id")->nullable();
            $table->unsignedInteger("col_span")->nullable();
            $table->string("background")->nullable();

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
        Schema::dropIfExists('rp_ym2m_rp_block_rp_pages');
    }
};
