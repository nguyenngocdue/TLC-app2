<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

Str::macro('appTitle', function (string $s) {
    $s = Str::plural($s);
    $s = Str::headline($s);
    $s = str_ireplace(
        ['hr', 'erp', 'wir', 'hse', 'esg', 'scm', 'qaqc', 'dev', 'kpi', 'qs', 'nz', 'dc', 'it', 'qa', 'qc', 'bd', 'prod', 'ho', 'ws'],
        ['HR', 'ERP', 'WIR', 'HSE', 'ESG', 'SCM', 'QAQC', 'DEV', 'KPI', 'QS', 'NZ', 'DC', 'IT', 'QA', 'QC', 'BD', 'PROD', 'HO', 'WS'],
        $s
    );

    $sources = ['acct', 'cpl', 'dir', 'fac', 'des', 'fin', 'mgr', 'pln', 'proc', 'proj', 'whs', 'asst'];
    $sources = array_map(fn ($i) => '/' . $i . '\b/u', $sources);
    $target =  ['Accounting', 'Compliance', 'Director', 'Factory', 'Design', 'Finance', 'Manager', 'Planning', 'Procurement', 'Project', 'Warehouse', 'Assistant'];
    $s = preg_replace($sources, $target, $s);
    return $s;
});
Str::macro('modelToPretty', function (string $string) {
    return Str::headline(App::make($string)->getTable());
});
Str::macro('same', function (string $string) {
    return $string;
});
Str::macro('makeId', function (string $id) {
    $numberRender = str_pad($id, 6, '0', STR_PAD_LEFT);
    $result = '#' . substr($numberRender, 0, 3) . '.' . substr($numberRender, 3, 6);
    return $result;
});
Str::macro('limitWords', function (string $str, $count, $maxLen = 50) {
    $i = $c = 0;
    while ($i < strlen($str)) {
        if ($str[$i] === ' ' && ++$c === $count) return substr($str, 0, $i) . " ...";
        $i++;
    }
    if (strlen($str) > $maxLen) $str = substr($str, 0, $maxLen) . " ...";
    return $str;
});

Str::macro('getEntityNameFromModelPath', function ($modelPaths) {
    $str =  str_replace('\\', '/', $modelPaths);
    return basename($str);
});
