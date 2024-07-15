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

        $schema->create('rp_blocks', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('sql_string')->nullable();
            $table->boolean('table_true_width')->nullable();
            $table->unsignedInteger('show_no')->nullable();
            $table->boolean('max_h')->nullable();
            $table->unsignedInteger('rotate_45_width')->nullable();
            $table->unsignedInteger('rotate_45_height')->nullable();
            $table->unsignedBigInteger('renderer_type')->nullable();
            $table->json('chart_json')->nullable();
            $table->boolean('has_pagination')->nullable();
            $table->unsignedBigInteger('top_left_control')->nullable();
            $table->unsignedBigInteger('top_center_control')->nullable();
            $table->unsignedBigInteger('top_right_control')->nullable();
            $table->unsignedBigInteger('bottom_left_control')->nullable();
            $table->unsignedBigInteger('bottom_center_control')->nullable();
            $table->unsignedBigInteger('bottom_right_control')->nullable();
            $table->unsignedBigInteger('chart_type')->nullable();
            $table->text('html_content')->nullable();

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
        Schema::dropIfExists('rp_blocks');
    }
};
