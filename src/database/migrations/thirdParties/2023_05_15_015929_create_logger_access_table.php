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
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        Schema::connection($connection)->create('logger_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->string('route_name');
            $table->string('env');
            $table->unsignedInteger('took');
            $table->string('url');

            $table->timestamp('created_at')->useCurrent();

            $table->index('env');
            $table->index('url');
            $table->index('route_name');
            $table->index('took');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logger_access');
    }
};
