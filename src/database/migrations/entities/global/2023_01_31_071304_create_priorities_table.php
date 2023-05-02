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

        $schema->create('priorities', function (BlueprintExtended $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();
            $table->float('duration');
            $table->unsignedBigInteger('field_id');
            $table->string('slug')->unique();
            $table->appendCommonFields();
        });
        // Schema::create('priorities', function (Blueprint $table) {
        //     $table->id();
        //     $table->text('name');
        //     $table->text('description')->nullable();
        //     $table->float('duration');
        //     $table->unsignedBigInteger('field_id');
        //     $table->string('slug')->unique();
        //     $table->unsignedBigInteger('owner_id');
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
        Schema::dropIfExists('priorities');
    }
};
