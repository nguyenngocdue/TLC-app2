<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\CurrentUser;

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

        $tableSettings = $editable ? $this->tablesInEditableMode[$this->type][$tableName] : [];

        $sp = SuperProps::getFor($tableName);
        $dataSourceWithOld = $this->convertOldToDataSource($this->table01Name, $dataSource, $lineModelPath);
        $editableColumns = $this->makeEditableColumns($columns, $sp, $tableName, $this->table01Name);
        if ($editable) {
            $this->alertIfFieldsAreMissingFromFillable($instance, $lineModelPath, $editableColumns);
        }
        //remakeOrderNoColumn MUST before attach Action Column
        $dataSourceWithOld = $this->remakeOrderNoColumn($dataSourceWithOld);
        $dataSourceWithOld = $this->attachActionColumn($this->table01Name, $dataSourceWithOld, $isOrderable, $this->readOnly);
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
        $dateTimeColumns = $sp['datetime_controls'];

        static::$cacheEloquentToTable01Name[$colName] = $this->table01Name;
        static::$cacheTable01NameToEloquent[$this->table01Name] = $colName;

        return view('components.controls.' . $view, [
            'readOnly' => $this->readOnly,
            'table01ROName' => $this->table01Name . "RO",
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
        ]);
    }
}
