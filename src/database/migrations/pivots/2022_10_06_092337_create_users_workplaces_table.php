<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected function checkRelationShip()
    {
        $relationship = '';
        if (strlen($relationship) > 1) {
            $name = md5(Str::plural('user') . '_' . Str::plural('workplace'));
            $tableName = Str::plural('user') . '_' . Str::plural('workplace') . '_' . $relationship;
        } else {
            $name = null;
            $tableName = Str::plural('user') . '_' . Str::plural('workplace');
        }
        return [$tableName, $name];
    }
    public function up()
    {

        Schema::create($this->checkRelationShip()[0], function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('workplace_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
            $table->primary(['user_id', 'workplace_id']);

            $table->foreign('user_id')->references('id')->on(Str::plural('user'))->onDelete('cascade');
            $table->foreign('workplace_id')->references('id')->on(Str::plural('workplace'))->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->checkRelationShip()[0]);
    }
};
