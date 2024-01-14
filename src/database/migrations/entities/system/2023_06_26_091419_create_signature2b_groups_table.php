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

        $schema->create('signature2b_groups', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            // $table->unsignedBigInteger('user_id')->nullable();
            // $table->text('signature_comment')->nullable();
            // $table->string('signature_decision')->nullable();
            $table->string('signable_type')->nullable();;
            $table->unsignedBigInteger('signable_id')->nullable();
            $table->unsignedBigInteger('category');
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
        Schema::dropIfExists('signature2b_groups');
    }
};
