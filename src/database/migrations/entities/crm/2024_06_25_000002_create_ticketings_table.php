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

        $schema->create('crm_ticketings', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->text('description')->nullable();

            $table->string('house_owner_id')->nullable();
            $table->string('house_owner_phone_number')->nullable();
            $table->string('house_owner_address')->nullable();
            $table->string('house_owner_email')->nullable();

            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->unsignedBigInteger('defect_cat_id')->nullable();
            $table->unsignedBigInteger('defect_sub_cat_id')->nullable();
            $table->unsignedBigInteger('assignee_1')->nullable();

            $table->string('appointment_title')->nullable();
            $table->dateTime('appointment_from')->nullable();
            $table->dateTime('appointment_to')->nullable();
            $table->string('appointment_detail')->nullable();

            $table->hasStatus();
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
        Schema::dropIfExists('crm_ticketings');
    }
};
