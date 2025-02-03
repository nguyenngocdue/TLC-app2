<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;

trait TraitTableRendererManyLines
{
    private static $cacheEloquentToTable01Name = [];
    private static $cacheTable01NameToEloquent = [];

    public static function getCacheEloquentToTable01Name()
    {
        return static::$cacheEloquentToTable01Name;
    }

    public static function getCacheTable01NameToEloquent()
    {
        return static::$cacheTable01NameToEloquent;
    }

    private function renderManyLines($tableName, $dataSource, $lineModelPath, $columns, $editable, $instance, $isOrderable, $colName, $tableFooter, $numberOfEmptyLines = 0)
    {
        $app = LibApps::getFor($tableName);
        $nickname = strtoupper($app['nickname'] ?? "no nickname");

        $createANewForm = isset($this->tablesHaveCreateANewForm[$this->type]);
        $createSettings = $createANewForm ? ($this->tablesHaveCreateANewForm[$this->type][$tableName] ?? []) : [];

        // $btnCmd = isset($this->tablesCallCmdBtn[$this->type]);
        // $btnCmdSettings = $btnCmd ? ($this->tablesCallCmdBtn[$this->type][$tableName] ?? []) : [];

        $tableSettingsOfEditable = $this->tablesInEditableMode[$this->type][$tableName] ?? [];
        $tableSettingsOfRO = $this->tablesHaveCreateANewForm[$this->type][$tableName] ?? [];
        $tableSettings = $editable ? $tableSettingsOfEditable : $tableSettingsOfRO;

        $sp = SuperProps::getFor($tableName);
        $dataSourceWithOld = $this->convertOldToDataSource($this->table01Name, $dataSource, $lineModelPath);
        $editableColumns = $this->makeEditableColumns($columns, $sp, $tableName, $this->table01Name);
        if ($editable) {
            $this->alertIfFieldsAreMissingFromFillable($instance, $lineModelPath, $editableColumns);
        }
        //remakeOrderNoColumn MUST before attach Action Column
        $dataSourceWithOld = $this->remakeOrderNoColumn($dataSourceWithOld);
        $dataSourceWithOld = $this->attachActionColumn($this->table01Name, $dataSourceWithOld, $isOrderable, $this->readOnly);

        //If rendering in Sequence screen
        if ($this->type === 'prod_sequences') {
            foreach ($dataSourceWithOld as &$item) {
                if ($item instanceof \App\Models\Prod_run) {
                    $dateToCheck = Carbon::parse($item->date);
                    $allowedDateOffset = config('production.prod_sequences.allowed_date_offset', 7);
                    // $sevenDaysAgo = Carbon::now()->subDays(7+1);
                    $fourDaysAgo = Carbon::now()->subDays($allowedDateOffset + 1);
                    if (!$dateToCheck->greaterThanOrEqualTo($fourDaysAgo)) {
                        $item->readOnly = true;
                    }
                }
            }
        }

        // dump($dataSourceWithOld);
        // dump($dataSource);
        // $tableName = $lineModelPath::getTableName();
        $roColumns = $this->makeReadOnlyColumns($columns, $sp, $tableName, $this->noCss);
        // dump($roColumns);

        //<<This is to avoid crash in print mode, as no item pass down
        $href = '';
        if ($this->item) {
            $itemOriginal = $this->item->getOriginal();
            $href = $this->createHref($this->item, $createSettings, $tableName);
        }
        $view = $editable ? 'many-line-params-editable' : 'many-line-params-ro';
        $dataSource2ndThead = $this->readOnly ? null : $this->makeEditable2ndThead($editableColumns, $this->table01Name);
        if ($this->readOnly) {
            array_shift($editableColumns);
            foreach ($editableColumns as &$column) {
                $column['properties']['readOnly'] = true;
            }
        }
        //ReadOnly from getManyLineParams
        foreach ($editableColumns as &$column) {
            if ($column['read_only_rr2'] ?? false) $column['properties']['readOnly'] = true;
        }
        $dateTimeColumns = $sp['datetime_controls'];

        static::$cacheEloquentToTable01Name[$colName] = $this->table01Name;
        static::$cacheTable01NameToEloquent[$this->table01Name] = $colName;

        $params = [
            'readOnly' => $this->readOnly,
            'table01ROName' => $this->table01Name, //. "RO",
            'readOnlyColumns' => $roColumns,
            'dataSource' => $dataSource,

            'isOrderable' => $isOrderable,
            'table01Name' => $this->table01Name,
            'editableColumns' => $editableColumns,
            'dateTimeColumns' => $dateTimeColumns,
            'dataSource2ndThead' => $dataSource2ndThead,
            'dataSourceWithOld' => $dataSourceWithOld,

            'tableFooter' => $tableFooter,
            'tableName' => $tableName,
            'nickname' => $nickname,
            'colName' => $colName,
            'tableDebug' => $this->tableDebug ? true : false,
            'tableDebugTextHidden' => $this->tableDebug ? "text" : "hidden",

            'entityId' => $this->entityId,
            'entityType' => $this->entityType,
            'userId' => CurrentUser::get()->id,
            'entityProjectId' => $itemOriginal['project_id'] ?? null,
            'entitySubProjectId' => $itemOriginal['sub_project_id'] ?? null,
            'item' => $itemOriginal,
            // 'entityCurrencyMonth' => $itemOriginal['rate_exchange_month_id'] ?? null,
            // 'entityCurrencyExpected' => $itemOriginal['counter_currency_id'] ?? null,

            'noCss' => $this->noCss,

            'editable' => $editable,
            'tableSettings' => $tableSettings,

            'createANewForm' => $createANewForm,
            // 'createSettings' => $createSettings,
            // 'btnCmdSettings' => $btnCmdSettings,
            'href' => $href,
            'showNo' => true,
            'showNoR' => false,
            'numberOfEmptyLines' => $numberOfEmptyLines,
        ];

        echo Blade::render('components.controls.many-line-params-common', $params);

        return view('components.controls.' . $view, $params);
    }
}
