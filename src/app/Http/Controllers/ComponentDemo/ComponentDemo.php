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
    use TraitDemoModeControl;

    function getType()
    {
        return "dashboard";
    }

    public function index()
    {
        $tableDataSource = $this->getTableDataSource();
        return view('component-demo', [
            'dropdownCell' => ["value" => "b", "cbbDS" => ["", "a", "b", "c"]],
            'tableColumns' => $this->getTableColumns(),
            'tableEditableColumns' => $this->getTableEditableColumns(),
            'tableDataHeader' => $this->getTableDataHeader(),
            'tableDataSource' => $tableDataSource,
            'gridDataSource' => $this->getGridData($tableDataSource),
            'dataComment' => $this->getCommendData(),
            'attachmentData' => $this->getAttachmentData(),
            'attachmentData2' => $this->getAttachmentData2(),
            'tagColumns' => $this->getTagColumns(),
            'tagDataSource' => $this->getTagDataSource(),
            'tabData1' => $this->getTab1(),
            'tabData2' => $this->getTab2(),
            'dataSource' => $this->getDataSource(),
            'itemsSelected' => $this->getItemsSelected()

        ]);
    }
}
