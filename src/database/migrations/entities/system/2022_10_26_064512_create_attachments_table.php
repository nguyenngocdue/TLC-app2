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

        $schema->create('attachments', function (BlueprintExtended $table) {
            $table->id();
            $table->string('url_thumbnail');
            $table->string('url_media');
            $table->string('url_folder');
            $table->string('filename');
            $table->string('extension');
            $table->string('mime_type');
            $table->string('object_type')->nullable();
            $table->unsignedBigInteger('object_id')->nullable();
            $table->unsignedBigInteger('category');
            $table->appendCommonFields();
        });
        // Schema::create('attachments', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('url_thumbnail');
        //     $table->string('url_media');
        //     $table->string('url_folder');
        //     $table->string('filename');
        //     $table->string('extension');
        //     $table->string('mime_type');
        //     $table->unsignedBigInteger('owner_id');
        //     $table->string('object_type')->nullable();
        //     $table->unsignedBigInteger('object_id')->nullable();
        //     $table->unsignedBigInteger('category');
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
        Schema::dropIfExists('attachments');
    }
};
