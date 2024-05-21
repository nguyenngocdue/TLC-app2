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

        $schema->create('hr_leave_lines', function (BlueprintExtended $table) {
            $table->id();

            $table->string("leaveable_type")->nullable();
            $table->unsignedBigInteger("leaveable_id")->nullable();

            $table->date("leave_date")->nullable();
            $table->unsignedBigInteger("leave_cat_id")->nullable();

            $table->unsignedBigInteger("leave_type_id")->nullable();
            $table->float("allowed_days")->nullable();
            $table->float("leave_days")->nullable();
            $table->float("remaining_days")->nullable();

            $table->unsignedBigInteger("workplace_id")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("remark")->nullable();

            $table->orderable();
            // $table->hasStatus();
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
        Schema::dropIfExists('hr_leave_lines');
    }
};
