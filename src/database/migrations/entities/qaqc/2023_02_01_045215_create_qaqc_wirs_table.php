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

        $schema->create('qaqc_wirs', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('doc_id');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->unsignedBigInteger('prod_routing_id')->nullable();
            $table->unsignedBigInteger('prod_order_id')->nullable();
            $table->unsignedBigInteger('prod_discipline_id')->nullable();
            $table->unsignedBigInteger('wir_description_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            // $table->dateTime('due_date')->nullable();
            $table->unsignedInteger('qc_total')->nullable();
            $table->unsignedInteger('qc_accepted')->nullable();
            $table->unsignedInteger('qc_remaining')->nullable();
            $table->unsignedInteger('qc_rejected')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();
            $table->hasDueDate();
            $table->closable();
            $table->appendCommonFields();
        });
        // Schema::create('qaqc_wirs', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->unsignedInteger('doc_id');
        //     $table->text('description')->nullable();
        //     $table->string('slug')->unique();
        //     $table->unsignedBigInteger('project_id')->nullable();
        //     $table->unsignedBigInteger('sub_project_id')->nullable();
        //     $table->unsignedBigInteger('prod_routing_id')->nullable();
        //     $table->unsignedBigInteger('prod_order_id')->nullable();
        //     $table->unsignedBigInteger('prod_discipline_id')->nullable();
        //     $table->unsignedBigInteger('wir_description_id')->nullable();
        //     $table->unsignedBigInteger('priority_id')->nullable();
        //     $table->dateTime('due_date')->nullable();
        //     $table->unsignedBigInteger('assignee_1')->nullable();
        //     $table->unsignedBigInteger('owner_id');
        //     $table->string('status')->nullable();
        //     $table->unsignedBigInteger('lock_version')->nullable();
        //     $table->dateTime('closed_at')->nullable();
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
        Schema::dropIfExists('qaqc_wirs');
    }
};
