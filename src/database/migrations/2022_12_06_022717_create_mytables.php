<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
        $relationship = 'rrr';
        $tableName = 'mytable';
        if (strlen($relationship) > 1) {
            $name = md5(Str::plural('attachments').'_'.Str::plural('custom_team'));
            $tableName = empty($tableName) ? Str::plural('attachments').'_'.Str::plural('custom_team').'_'.$relationship : $tableName;
        } else {
            $name = null;
            $tableName = empty($tableName) ? Str::plural('attachments').'_'.Str::plural('custom_team') : $tableName;
        }
        return [$tableName,$name];
    }
    public function up()
    {
        
        Schema::create($this->checkRelationShip()[0], function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attachments_id');
            $table->unsignedBigInteger('custom_team_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
            $table->unique(['attachments_id', 'custom_team_id'],$this->checkRelationShip()[1]);
            $table->foreign('attachments_id')->references('id')->on(Str::plural('attachments'))->onDelete('cascade');
            $table->foreign('custom_team_id')->references('id')->on(Str::plural('custom_team'))->onDelete('cascade');
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
