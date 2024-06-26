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

        $schema->create('rp_columns', function (BlueprintExtended $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active');
            $table->string('data_index');
            $table->integer('width')->nullable();
            $table->text('cell_div_class_agg_footer')->nullable();
            $table->text('cell_div_class')->nullable();
            $table->text('cell_class')->nullable();
            $table->text('icon')->nullable();
            $table->integer('icon_position')->nullable();
            $table->text('row_cell_div_class')->nullable();
            $table->text('row_cell_class')->nullable();
            $table->text('row_icon')->nullable();
            $table->integer('row_icon_position')->nullable();
            $table->text('row_href_fn')->nullable();
            $table->unsignedBigInteger('row_renderer')->nullable();
            $table->unsignedBigInteger('agg_footer')->nullable();

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
        Schema::dropIfExists('rp_columns');
    }
};
