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

        $schema->create('ym2m_workplace_zunit_test_01', function (BlueprintExtended $table) {
            $table->id();
            $table->foreignId('workplace_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('zunit_test_01_id')->constrained()->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['workplace_id', 'zunit_test_01_id'], md5('workplace_id' . 'zunit_test_01_id'));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ym2m_workplace_zunit_test_01');
    }
};
