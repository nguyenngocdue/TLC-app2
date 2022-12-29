<?php

namespace App\BigThink;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait MetaForChart
{
    function sqlForeignKey($table_a, $table_b, $key_a = null, $key_b = null, $global_filter = '1=1')
    {
        $key_a = $key_a ?? Str::singular($table_b) . "_id";
        $key_b = $key_b ?? "id";
        // Log::info("$table_a, $table_b, $key_a, $key_b");

        $sql = "SELECT a.$key_a metric_id, b.name metric_name, count(a.id) metric_count
            FROM $table_a a, $table_b b
            WHERE 1=1
                AND $global_filter
                AND b.$key_b=a.$key_a
            GROUP BY $key_a
            ORDER BY metric_count DESC
            ";
        return DB::select($sql);
    }

    function getMetaForChart()
    {
        // $result = $this->sqlForeignKey("users", "workplaces", "workplace", "id", "a.resigned != true");
        // $result = $this->sqlForeignKey("users", "user_categories", "category", "id", "a.resigned != true");
        // $result = $this->sqlForeignKey("users", "user_types", "user_type", "id", "a.resigned != true");
        // $result = $this->sqlForeignKey("users", "user_position1s", "position_1", "id", "a.resigned != true");
        // $result = $this->sqlForeignKey("users", "user_position2s", "position_2", "id", "a.resigned != true");
        // $result = $this->sqlForeignKey("users", "user_disciplines", "discipline", "id", "a.resigned != true");
        // $result = $this->sqlForeignKey("users", "departments", "department", "id", "a.resigned != true");

        $result = $this->sqlForeignKey("prod_orders", "sub_projects", "sub_project_id");
        $result = $this->sqlForeignKey("prod_orders", "prod_routings", "prod_routing_id");

        return $result;
        return [
            "total" => 123,
            "metric" => [
                "workplace" => [
                    "vn-ho" => 15,
                    "nz-o" => 2,
                ],
                "category" => [
                    "A" => 45,
                ],
            ],
        ];
    }
}
