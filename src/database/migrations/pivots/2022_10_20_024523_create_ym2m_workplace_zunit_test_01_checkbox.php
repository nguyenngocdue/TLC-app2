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

        $schema->create('ym2m_workplace_zunit_test_01_checkbox', function (BlueprintExtended $table) {
            $table1 = "workplaces";
            $key1 = "workplace_id";
            $table2 = "zunit_test_01s";
            $key2 = "zunit_test_01_id";
            $key3 = "checkbox";

            $table->id();
            // $table->foreignId($key1)->constrained()->onDelete('cascade')->onUpdate('cascade')->name($key1 . $key3);
            $table->unsignedBigInteger($key1);
            $table->foreign($key1, "$key1+$key3")->references('id')->on($table1)->onDelete('cascade')->onUpdate('cascade');

            // $table->foreignId($key2)->constrained()->onDelete('cascade')->onUpdate('cascade')->name($key2 . $key3);
            $table->unsignedBigInteger($key2);
            $table->foreign($key2, "$key2+$key3")->references('id')->on($table2)->onDelete('cascade')->onUpdate('cascade');

            $table->unique([$key1, $key2], md5($key1 . $key2 . $key3));

            $table->unsignedBigInteger('owner_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ym2m_workplace_zunit_test_01_checkbox');
    }
};
