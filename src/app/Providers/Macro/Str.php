<?php

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Project;
use App\Models\Sub_project;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

Str::macro('appTitle', function (string $s) {
    $exceptionOfKeepingSingular = ['qaqc', 'hse', 'kpi', 'esg', 'hr', 'scm'];
    $s = in_array($s, $exceptionOfKeepingSingular) ? $s : Str::plural($s);
    $s = Str::headline($s);

    $sources = ['hr', 'erp', 'wir', 'hse', 'esg', 'scm', 'qaqc', 'dev', 'kpi', 'qs', 'nz', 'dc', 'it', 'qa', 'qc', 'bd', 'prod', 'ho', 'ws'];
    // /i is for case insensitive
    $sources = array_map(fn ($i) => '/\b' . $i . '\b/i', $sources);
    $target = ['HR', 'ERP', 'WIR', 'HSE', 'ESG', 'SCM', 'QAQC', 'DEV', 'KPI', 'QS', 'NZ', 'DC', 'IT', 'QA', 'QC', 'BD', 'PROD', 'HO', 'WS'];
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
    return ($values != "") ? explode(",", $values) : [];
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
Str::macro('markDocId', function ($dataSource, $type = null) {
    if (!$type) {
        $type = $dataSource->getTable();
    }
    $docId = $dataSource['doc_id'] ?? '';
    $libAppsData = LibApps::getFor($type);
    $docIdName = '';
    if ($nameColumnDocIDFormat = $libAppsData['doc_id_format_column']) {
        $organizationName = env('ORGANIZATION_NAME', 'TLC');
        $entityNickName = strtoupper($libAppsData['nickname']);
        switch ($nameColumnDocIDFormat) {
            case 'project_id':
                $nameProjectOrSubProject = Project::find($dataSource['project_id'])->name
                    ?? $dataSource['project_id'] ?? '';
                break;
            case 'sub_project_id':
                $nameProjectOrSubProject = Sub_project::find($dataSource['sub_project_id'])->name
                    ?? $dataSource['sub_project_id'] ?? '';
                break;
            default:
                break;
        }
        $result = [
            $organizationName,
            $nameProjectOrSubProject,
            $entityNickName,
            sprintf('%04d', $docId)
        ];
        $docIdName = implode('-', $result);
    }
    return $docIdName;
});
Str::macro('arrayToAttrs', function (array $array) {
    $result = [];
    foreach ($array as $key => $value) {
        $result[] = "$key='$value'";
    }
    return join(" ", $result);
});
