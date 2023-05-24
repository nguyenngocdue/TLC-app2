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
            $table->unsignedBigInteger('line_manager');
            $table->string('manager_staff_id');
            $table->string('manager_staff_position');
            $table->string('owner_staff_id');
            $table->string('owner_staff_position');
            $table->double('number_injured_person');
            $table->double('number_involved_person');
            $table->text('issue_description');

            $table->date('first_date');
            $table->float('employed_duration_in_year');
            $table->unsignedBigInteger('injured_staff_department');
            $table->string('loss_value')->nullable();
            $table->boolean('need_to_transfer_position')->nullable();

            $table->boolean('accident_book_entry')->nullable();
            $table->dateTime('time_in_hospital')->nullable();
            $table->dateTime('time_out_hospital')->nullable();
            $table->float('lost_days')->nullable();
            $table->text('investigation_finding')->nullable();
            $table->appendCommonFields();
        });

        // Schema::create('hse_incident_reports', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->unsignedBigInteger('work_area_id');
        //     $table->dateTime('issue_datetime');
        //     $table->unsignedBigInteger('incident_doc_type_id');
        //     $table->unsignedBigInteger('incident_doc_sub_type_id');
        //     $table->unsignedBigInteger('injured_person');
        //     $table->string('injured_staff_id');
        //     $table->string('injured_staff_position');
        //     $table->unsignedBigInteger('line_manager');
        //     $table->string('manager_staff_id');
        //     $table->string('manager_staff_position');
        //     $table->unsignedBigInteger('owner_id');
        //     $table->string('owner_staff_id');
        //     $table->string('owner_staff_position');
        //     $table->double('number_injured_person');
        //     $table->double('number_involved_person');
        //     $table->text('issue_description');
        //     $table->boolean('accident_book_entry')->nullable();
        //     $table->dateTime('time_in_hospital')->nullable();
        //     $table->dateTime('time_out_hospital')->nullable();
        //     $table->float('lost_days')->nullable();
        //     $table->text('investigation_finding')->nullable();
        //     $table->string('status')->nullable();
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
        Schema::dropIfExists('hse_incident_reports');
    }
};
