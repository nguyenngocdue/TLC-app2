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
        DB::statement("CREATE OR REPLACE VIEW view_full_prod_sequence AS
        (
            SELECT 
                view_prl.*, 
                ps.id ps_id,
                ps.priority ps_priority,
                ps.total_hours,
                ps.total_man_hours,
                ps.expected_start_at, 
                ps.expected_finish_at
            FROM 
                view_full_prod_routing_link view_prl,
                prod_sequences ps
            WHERE 1=1
                AND po_id = ps.prod_order_id
                AND prl_id = ps.prod_routing_link_id
            ORDER BY `po_id` ASC
        )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_full_prod_sequence');
    }
};
