<?php

namespace App\Http\Controllers\ComponentDemo\DataSource;

class CsvLoader
{
    static function getFromFile($fileName, $dir = __DIR__ . "/csv")
    {
        $content = file_get_contents($dir . "/" . $fileName);
        $lines = explode("\n", $content);
        $lines = array_filter($lines, fn ($l) => $l);
        $columns = explode(",", array_shift($lines));
        $dataSource = [];
        foreach ($lines as $line) {
            $cells = explode(",", $line);
            $row = [];
            foreach ($cells as $index => $cell) {
                $row[$columns[$index]] = $cell;
            }
            $dataSource[] = $row;
        }
        // dump($columns);
        // dump($dataSource);
        // dump($lines);
        // dd();
        $columns = array_map(fn ($c) => ['dataIndex' => $c], $columns);
        return [$columns, $dataSource];
    }
}
