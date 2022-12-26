<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('hse_incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('issue_location');
            $table->dateTime('issue_datetime');
            $table->unsignedBigInteger('injured_person');
            $table->unsignedBigInteger('line_manager');
            $table->unsignedBigInteger('report_person');
            $table->double('number_injured_person');
            $table->double('number_involved_person');
            $table->text('issue_description');
            $table->boolean('accident_book_entry');
            $table->dateTime('time_in_hospital');
            $table->dateTime('time_out_hospital');

            $table->text('investigation_finding');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
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
