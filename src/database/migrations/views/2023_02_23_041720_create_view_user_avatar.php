<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW view_user_avatar AS
        
            SELECT u.id AS u_id, att.id AS att_id, att.url_thumbnail
                FROM users u 
                    LEFT JOIN attachments att ON (
                        u.id=att.object_id 
                        AND att.object_type='App\\\\Models\\\\User'
                    )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_user_avatar');
    }
};
