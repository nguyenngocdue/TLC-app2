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

        $schema->create('comments', function (BlueprintExtended $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->text('position_rendered');
            $table->string('commentable_type')->nullable();;
            $table->unsignedBigInteger('commentable_id')->nullable();
            $table->unsignedBigInteger('category');
            $table->appendCommonFields();
        });
        // Schema::create('comments', function (Blueprint $table) {
        //     $table->id();
        //     $table->text('content')->nullable();
        //     $table->unsignedBigInteger(('owner_id'));
        //     $table->text('position_rendered');
        //     $table->string('commentable_type')->nullable();;
        //     $table->unsignedBigInteger('commentable_id')->nullable();
        //     $table->unsignedBigInteger('category');
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
        Schema::dropIfExists('comments');
    }
};
