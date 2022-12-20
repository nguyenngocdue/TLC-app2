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
        Schema::create('many_to_many', function (Blueprint $table) {
            // $table->id(); 
            $table->unsignedBigInteger('field_id');
            $table->string('doc_type');
            $table->unsignedBigInteger('doc_id');
            $table->string('term_type');
            $table->unsignedBigInteger('term_id');
            $table->json('json');

            $table->primary(['field_id', 'doc_type', 'doc_id', 'term_type', 'term_id'], md5('many_to_many_field_id_doc_type_doc_id_term_type_term_id_primary'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('many_to_many');
    }
};
