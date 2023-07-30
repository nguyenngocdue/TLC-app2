<?php

namespace App\Utils\Excel;
class  Excel implements IExcel{
    public static function header($fileName): array {
        return array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
    }
    public static function export($columns,$dataSource)
    {
        $callback = function () use ($dataSource, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $array = [];
            foreach ($dataSource as $row) {
                foreach ($columns as $key => $column) {
                    $array[$key] = $row[$key] ?? null;
                }
                fputcsv($file, $array);
            }
            fclose($file);
        };
        return $callback;
    }
}