<?php

namespace App\Http\Controllers\ComponentDemo;

use App\Http\Controllers\ComponentDemo\DataSource\CsvLoader;

class ComponentDemo
{
    use TraitDemoAttachmentData;
    use TraitDemoIconsData;
    use TraitDemoCommentData;
    use TraitDemoGridData;
    use TraitDemoTableData;
    use TraitDemoTagData;
    use TraitDemoTabData;
    use TraitDemoProgressBar;
    use TraitDemoSpanTable;
    // use TraitDemoModeControl;
    // use TraitDemoPivotTable;

    function getType()
    {
        return "dashboard";
    }

    private function getTabPaneDataSource()
    {
        return [
            'static' => ['href' => "#static", 'title' => "Static", 'active' => false,],
            'icons' => ['href' => "#icons", 'title' => "Icons", 'active' => false,],
            'data_display' => ['href' => "#data_display", 'title' => "Data Display", 'active' => false,],
            'data_entry' => ['href' => "#data_entry", 'title' => "Data Entry", 'active' => false,],
            'attachments' => ['href' => "#attachments", 'title' => "Attachments", 'active' => false,],
            'editable_tables' => ['href' => "#editable_tables", 'title' => "Editable Tables", 'active' => false,],
            'navigation' => ['href' => "#navigation", 'title' => "Navigation", 'active' => false,],
            'feedbacks' => ['href' => "#feedbacks", 'title' => "Feedback", 'active' => false,],
            'listeners' => ['href' => "#listeners", 'title' => "Listeners", 'active' => false,],
            'pivot_tables' => ['href' => "#pivot_tables", 'title' => "Pivot Tables", 'active' => false,],
            'pivot_tables2' => ['href' => "#pivot_tables2", 'title' => "Pivot Tables 2", 'active' => false,],
            'charts' => ['href' => "#charts", 'title' => "Charts", 'active' => true,],
            // 'modecontrols' => ['href' => "#modecontrols", 'title' => "Mode Controls", 'active' => false,],
        ];
    }

    public function index()
    {
        $tableDataSource = $this->getTableDataSource();

        [$pivot1Columns, $pivot1Data] = CsvLoader::getFromFile("timesheet_lines.csv");
        [$pivot2Columns, $pivot2Data] = CsvLoader::getFromFile("apple_products.csv");

        return view('component-demo', [
            'tabPaneDataSource' => $this->getTabPaneDataSource(),
            'dropdownCell' => ["value" => "b", "cbbDS" => ["", "a", "b", "c"]],
            'tableColumns' => $this->getTableColumns(),
            'tableEditableColumns' => $this->getTableEditableColumns(),
            'tableDataHeader' => $this->getTableDataHeader(),
            'tableDataSource' => $tableDataSource,
            'gridDataSource' => $this->getGridData($tableDataSource),
            'dataComment' => [$this->makeComment(0), $this->makeComment(1),],
            'attachmentData' => $this->getAttachmentData(),
            'attachmentData2' => $this->getAttachmentData2(),
            'tagColumns' => $this->getTagColumns(),
            'tagDataSource' => $this->getTagDataSource(),
            'tabData1' => $this->getTab1(),
            'tabData2' => $this->getTab2(),
            'iconsColumns' => $this->getIconsColumns(),
            'iconsDataSource' => $this->getIconsDataSource(),
            // 'listenerDataSource' => $this->getDataSource(), //??
            // 'itemsSelected' => $this->getItemsSelected(), //??
            'pivotTableColumns' => $pivot1Columns,
            'pivotTableDataSource' => $pivot1Data,
            'pivotTableColumns2' => $pivot2Columns,
            'pivotTableDataSource2' => $pivot2Data,
            'tableDataSourceForRegister' => $this->getDataSourceForRegister(),
            'tableColumnsForRegister' => $this->getColumnsForRegister(),
            'dataSourceProgressBar' => $this->getDataSourceProgressBar(),
            'tableSpanColumns' => $this->getTableSpanColumns(),
            'tableSpanDataSource' => $this->getTableSpanDataSource(),
        ]);
    }
}
