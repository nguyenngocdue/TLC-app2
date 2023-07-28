<?php

namespace App\Utils\Excel;

interface IExcel {
    public static function header(string $filename) : array;
    public static function export(array $columns,array $dataSource);
}