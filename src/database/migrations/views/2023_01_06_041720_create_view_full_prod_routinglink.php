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
        DB::statement("CREATE OR REPLACE VIEW views_full_prod_routing_link AS
        (
            SELECT 
                po.id po_id, 
                po.sub_project_id sp_id,
                sp.name sp_name,
                po.name po_name, 
                po.production_name po_prod, 
                po.compliance_name po_comp, 
                po.quantity po_qty, 
                po.started_at po_started_at,
                po.status po_status, 
                po.prod_routing_id pr_id, 
                prl.id prl_id, 
                prl.name prl_name,
                prd.target_hours, 
                prd.target_man_hours
            FROM 
                prod_orders po, 
                prod_routing_details prd, 
                prod_routing_links prl,
                sub_projects sp
            WHERE 1=1
                AND po.prod_routing_id = prd.prod_routing_id
                AND prd.prod_routing_link_id = prl.id
                AND po.sub_project_id = sp.id
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
        DB::statement('DROP VIEW IF EXISTS views_full_prod_routing_link');
    }
};
