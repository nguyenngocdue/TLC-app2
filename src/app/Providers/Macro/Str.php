<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

Str::macro('appTitle', function (string $s) {
    $exceptionOfKeepingSingular = ['hse', 'kpi', 'esg', 'ghg', 'hr', 'scm', 'eco'];
    $s = in_array($s, $exceptionOfKeepingSingular) ? $s : Str::plural($s);
    $s = Str::headline($s);

    $sources = ['hr', 'erp', 'wir', 'hse', 'esg', 'ghg', 'scm', 'qaqc', 'dev', 'kpi', 'qs', 'nz', 'dc', 'it', 'qa', 'qc', 'bd', 'prod', 'ho', 'ws', 'pj', 'eco'];
    // /i is for case insensitive
    $sources = array_map(fn ($i) => '/\b' . $i . '\b/i', $sources);
    $target = ['HR', 'ERP', 'WIR', 'HSE', 'ESG', 'GHG', 'SCM', 'QAQC', 'DEV', 'KPI', 'QS', 'NZ', 'DC', 'IT', 'QA', 'QC', 'BD', 'PROD', 'HO', 'WS', 'PJ', 'ECO'];
    $s = preg_replace($sources, $target, $s);

    $sources = ['acct', 'cpl', 'dir', 'fac', 'des', 'fin', 'mgr', 'pln', 'proc', 'proj', 'whs', 'asst'];
    // /u is for whole word
    $sources = array_map(fn ($i) => '/\b' . $i . '\b/u', $sources);
    $target =  ['Accounting', 'Compliance', 'Director', 'Factory', 'Design', 'Finance', 'Manager', 'Planning', 'Procurement', 'Project', 'Warehouse', 'Assistant'];
    $s = preg_replace($sources, $target, $s);
    return $s;
});
Str::macro('removeFirstSlash', function (string $s) {
    if ($s[0] === '/') return substr($s, 1);
    return $s;
});
Str::macro('getFieldNameInTable01Format', function (string $name, string $table01Name) {
    $isStartWith = $table01Name && str_starts_with($name, $table01Name);
    if ($isStartWith) {
        $name = substr($name, strlen($table01Name) + 1); // table01[hse_incident_report_id][0] => hse_incident_report_id][0]
        $name = substr($name, 0, strpos($name, "]"));
    }
    return $name;
});
Str::macro('modelPathFrom', function (string $table_or_type) {
    return "App\\Models\\" . ucfirst(Str::singular($table_or_type));
});
//make a,b,c to [a,b,c]
//make "" to []
Str::macro('parseArray', function (?string $values) {
    $array = ($values != "") ? explode(",", $values) : [];
    return array_map(fn ($s) => trim($s), $array);
});

// Str::macro('modelToPretty', function (string $string) {
//     return Str::headline(App::make($string)->getTable());
// });
Str::macro('same', function (string $string) {
    return $string;
});
Str::macro('makeId', function (string $id) {
    $numberRender = str_pad($id, 6, '0', STR_PAD_LEFT);
    $result = '#' . substr($numberRender, 0, 3) . '.' . substr($numberRender, 3, 6);
    return $result;
});
Str::macro('limitWords', function (string $str, $count, $maxLen = 50) {
    if (str_starts_with($str, "<svg",)) return $str;
    $i = $c = 0;
    while ($i < strlen($str)) {
        if ($str[$i] === ' ' && ++$c === $count) return substr($str, 0, $i) . " ...";
        $i++;
    }
    if (strlen($str) > $maxLen) {
        $lastSpace = strrpos($str, ' '); //, $maxLen - 10); //Some time last word longer than 10 chars will cause bug
        $str = substr($str, 0, $lastSpace) . " ...";
    }
    return $str;
});

// Str::macro('getEntityNameFromModelPath', function ($modelPaths) {
//     $str =  str_replace('\\', '/', $modelPaths);
//     return basename($str);
// });

Str::macro('getPickerPlaceholder', function ($control) {
    switch ($control) {
        case "picker_datetime":
            return "DD/MM/YYYY HH:MM";
        case "picker_time":
            return "HH:MM";
        case "picker_date":
            return "DD/MM/YYYY";
        case "picker_month":
            return "MM/YYYY";
        case "picker_week":
            return "W01/YYYY";
        case "picker_quarter":
            return "Q1/YYYY";
        case "picker_year":
            return "YYYY";
        default:
            return "??? $control ???";
    }
});
Str::macro('getEntityName', function ($entity) {
    $arr = explode('\\', get_class($entity));
    $result = Str::plural(strtolower(end($arr)));
    return $result;
});
Str::macro('humanReadable', function ($bytes, $base = 1000, $dec = 1) {
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor == 0) $dec = 0;
    $size   = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');

    return sprintf("%.{$dec}f%s", $bytes / ($base ** $factor), $size[$factor]);
});

Str::macro('removeDuplicateSpaces', function ($str) {    // Use regular expression to remove duplicate spaces
    // $pattern = '/\s+/'; //<< This will also remove new line, which will make the div's title in one line
    $pattern = '/[ \t]+/';
    $replacement = ' ';
    $result = preg_replace($pattern, $replacement, $str);

    return $result;
});

Str::macro('getExtFromMime', function ($mime) {
    switch ($mime) {
        case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
            return "docx";
        case "application/x-zip-compressed":
            return "zip";
        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
            return "xlsx";
        case "application/pdf":
            return "pdf";

        case "image/webp":
            return "webp";
        case "image/avif":
            return "avid";
        case "image/png":
            return "png";
        case "image/svg+xml":
            return "svg";
        case "image/jpeg":
            return "jpg";

        case "video/mp4":
            return "mp4";
        case "video/quicktime":
            return "mov";
    }
});
