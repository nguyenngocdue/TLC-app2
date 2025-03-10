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

        $schema->create('hr_leave_cats', function (BlueprintExtended $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->unsignedBigInteger("workplace_id")->nullable();
            $table->string("tpto_key")->nullable();
            $table->string("remark")->nullable();
            // $table->orderable();
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
        Schema::dropIfExists('hr_leave_cats');
    }
};
