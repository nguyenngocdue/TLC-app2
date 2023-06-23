<?php

namespace App\Http\Controllers\ComponentDemo;

class ComponentDemo
{
    use TraitDemoAttachmentData;
    use TraitDemoCommentData;
    use TraitDemoGridData;
    use TraitDemoTableData;
    use TraitDemoTagData;
    use TraitDemoTabData;
    use TraitDemoProgressBar;
    // use TraitDemoModeControl;
    use TraitDemoPivotTable;

    function getType()
    {
        return "dashboard";
    }

    private function getTabPaneDataSource()
    {
        return [
            'static' => ['href' => "#static", 'title' => "Static", 'active' => false,],
            'data_display' => ['href' => "#data_display", 'title' => "Data Display", 'active' => false,],
            'data_entry' => ['href' => "#data_entry", 'title' => "Data Entry", 'active' => false,],
            'attachments' => ['href' => "#attachments", 'title' => "Attachments", 'active' => false,],
            'editable_tables' => ['href' => "#editable_tables", 'title' => "Editable Tables", 'active' => false,],
            'navigation' => ['href' => "#navigation", 'title' => "Navigation", 'active' => false,],
            'feedbacks' => ['href' => "#feedbacks", 'title' => "Feedback", 'active' => false,],
            'listeners' => ['href' => "#listeners", 'title' => "Listeners", 'active' => false,],
            'pivot_tables' => ['href' => "#pivot_tables", 'title' => "Pivot Tables", 'active' => true,],
            // 'modecontrols' => ['href' => "#modecontrols", 'title' => "Mode Controls", 'active' => false,],
        ];
    }

    public function index()
    {
        $tableDataSource = $this->getTableDataSource();
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
            // 'listenerDataSource' => $this->getDataSource(), //??
            // 'itemsSelected' => $this->getItemsSelected(), //??
            'pivotTableColumns' => $this->getPivotTableColumns1(),
            'pivotTableDataSource' => $this->getPivotTableDataSource1(),
            'tableDataSourceForRegister' => $this->getDataSourceForRegister(),
            'tableColumnsForRegister' => $this->getColumnsForRegister(),
            'dataSourceProgressBar' => $this->getDataSourceProgressBar(),
        ]);
    }
}
