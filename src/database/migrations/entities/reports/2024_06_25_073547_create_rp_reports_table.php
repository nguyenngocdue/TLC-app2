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

        $schema->create('rp_reports', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string("title")->nullable();
            $table->text('description')->nullable();
            $table->string('entity_type')->nullable();
            $table->boolean('has_time_range')->nullable();
            $table->string("default_time_range")->nullable();
            $table->boolean('disable_from_date')->nullable();
            
            $table->boolean('is_active')->nullable();
            $table->string('breadcrumb_group')->nullable();
            $table->string('sub_package')->nullable();
            $table->string('icon')->nullable();

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
        Schema::dropIfExists('rp_reports');
    }
};
