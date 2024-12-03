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

        $schema->create("rp_pages", function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("title")->nullable();
            $table->unsignedBigInteger("report_id")->nullable();
            $table->unsignedBigInteger("iterator_block_id")->nullable();
            $table->boolean("is_active")->nullable();
            $table->unsignedBigInteger("letter_head_id")->nullable();
            $table->unsignedBigInteger("letter_footer_id")->nullable();
            $table->boolean("is_landscape")->nullable();
            $table->unsignedInteger("width")->nullable();
            $table->unsignedInteger("height")->nullable();
            $table->boolean("is_stackable_letter_head")->nullable();
            $table->boolean("is_full_width")->nullable();
            $table->text("page_body_class")->nullable();

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
        Schema::dropIfExists('rp_pages');
    }
};
