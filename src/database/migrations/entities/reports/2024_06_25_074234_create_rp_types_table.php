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

        $schema->create('rp_types', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('block_id');
            $table->boolean('table_true_width');
            $table->unsignedInteger('max_h');
            $table->unsignedInteger('rotate_45_width')->nullable();
            $table->unsignedInteger('rotate_45_height')->nullable();
            $table->unsignedBigInteger('renderer_type');
            $table->json('chart_json')->nullable();
            $table->boolean('has_pagination');
            $table->unsignedBigInteger('top_left_control')->nullable();
            $table->unsignedBigInteger('top_center_control')->nullable();
            $table->unsignedBigInteger('top_right_control')->nullable();
            $table->unsignedBigInteger('bottom_left_control')->nullable();
            $table->unsignedBigInteger('bottom_center_control')->nullable();
            $table->unsignedBigInteger('bottom_right_control')->nullable();
            $table->unsignedBigInteger('chart_type')->nullable();
            $table->text('html_content')->nullable();
            $table->string('html_attachment')->nullable();

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
        Schema::dropIfExists('rp_types');
    }
};
