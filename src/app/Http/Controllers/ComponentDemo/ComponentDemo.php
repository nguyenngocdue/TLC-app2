<?php

namespace App\Http\Controllers\ComponentDemo;

class ComponentDemo
{
    use TraitAttachmentData;
    use TraitCommentData;
    use TraitGridData;
    use TraitTableData;
    use TraitTagData;
    use TraitTabData;

    function getType()
    {
        return "dashboard";
    }

    public function index()
    {
        $tableDataSource = $this->getTableDataSource();
        return view('component-demo', [
            'tableColumns' => $this->getTableColumns(),
            'tableEditableColumns' => $this->getTableEditableColumns(),
            'tableDataHeader' => $this->getTableDataHeader(),
            'tableDataSource' => $tableDataSource,
            'gridDataSource' => $this->getGridData($tableDataSource),
            'dataComment' => $this->getCommendData(),
            'attachmentData' => $this->getAttachmentData(),
            'tagColumns' => $this->getTagColumns(),
            'tagDataSource' => $this->getTagDataSource(),
            'tabData1' => $this->getTab1(),
            'tabData2' => $this->getTab2(),
        ]);
    }
}
