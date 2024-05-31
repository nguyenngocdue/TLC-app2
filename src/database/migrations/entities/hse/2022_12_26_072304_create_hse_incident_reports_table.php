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

        $schema->create('hse_incident_reports', function (BlueprintExtended $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('work_area_id');
            $table->dateTime('issue_datetime');
            $table->unsignedBigInteger('incident_doc_type_id');
            $table->unsignedBigInteger('incident_doc_sub_type_id');
            $table->unsignedBigInteger('injured_person');
            $table->string('injured_staff_id');
            $table->string('injured_staff_position');
            $table->unsignedBigInteger('assignee_1');
            $table->unsignedBigInteger('assignee_2')->nullable();
            $table->unsignedBigInteger('assignee_3')->nullable();
            $table->string('manager_staff_id');
            $table->string('manager_staff_position');
            $table->string('owner_staff_id');
            $table->string('owner_staff_position');
            $table->double('number_injured_person');
            $table->double('number_involved_person');
            $table->text('issue_description');

            $table->date('first_date');
            $table->float('employed_duration_in_year');
            $table->unsignedBigInteger('injured_staff_cat');
            $table->string('injured_staff_cat_desc');
            $table->unsignedBigInteger('injured_staff_discipline');
            $table->float('lost_value')->nullable();
            $table->unsignedBigInteger('lost_unit_id')->nullable();
            $table->boolean('need_to_transfer_position')->nullable();

            $table->boolean('accident_book_entry')->nullable();
            $table->dateTime('time_in_hospital')->nullable();
            $table->dateTime('time_out_hospital')->nullable();
            $table->float('lost_days')->nullable();
            $table->text('investigation_finding')->nullable();
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
        Schema::dropIfExists('hse_incident_reports');
    }
};
