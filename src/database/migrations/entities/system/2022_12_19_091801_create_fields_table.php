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

        $schema->create('fields', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('reversed_name')->nullable();
            $table->text('description')->nullable();
            // $table->string('slug')->unique(); //<<Very annoying
            $table->appendCommonFields();
        });
        // Schema::create('fields', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('reversed_name')->nullable();
        //     $table->text('description')->nullable();
        //     $table->string('slug')->unique();
        //     $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
};
