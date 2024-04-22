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
        $schema->blueprintResolver(function ($table, $callback) {
            return new BlueprintExtended($table, $callback);
        });

        $schema->create('user_sub_cats', function (BlueprintExtended $table) {
            $table->id();
            $table->text('name')->nullable()->comment('Nameless');
            $table->text('description')->nullable();

            $table->foreignId('user_category_id')->constrained();
            $table->foreignId('user_type_id')->constrained();
            $table->appendCommonFields();

            $table->unique(['user_type_id', 'user_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sub_cats');
    }
};
