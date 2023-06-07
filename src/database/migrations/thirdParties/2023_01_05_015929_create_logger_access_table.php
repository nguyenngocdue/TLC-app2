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
        if (!env('ACCESS_LOGGER_ENABLED')) return;
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        Schema::connection($connection)->create('logger_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->string('route_name');
            $table->string('entity_name');
            $table->string('entity_id')->nullable();
            $table->string('env');
            $table->unsignedInteger('took');
            $table->float('memory_in_mb');
            $table->string('url');

            $table->timestamp('created_at')->useCurrent();

            $table->index('env');
            $table->index('url');
            $table->index('route_name');
            $table->index('took');
            $table->index('entity_name');
            $table->index('entity_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!env('ACCESS_LOGGER_ENABLED')) return;
        $connection = env('TELESCOPE_DB_CONNECTION', 'mysql');
        Schema::connection($connection)->dropIfExists('logger_access');
    }
};
