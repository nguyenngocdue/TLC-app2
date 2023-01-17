<?php

namespace App\Http\Controllers\ComponentDemo;

class ComponentDemo
{
    use TraitAttachmentData;
    use TraitCommentData;
    use TraitGridData;
    use TraitTableData;
    use TraitTagData;

    function getType()
    {
        return "dashboard";
    }

    public function index()
    {
        $tableDataSource = $this->getTableDataSource();
        return view('component-lib', [
            'tableColumns' => $this->getTableColumns(),
            'tableEditableColumns' => $this->getTableEditableColumns(),
            'tableDataSource' => $tableDataSource,
            'gridDataSource' => $this->getGridData($tableDataSource),
            'dataComment' => $this->getCommendData(),
            'attachmentData' => $this->getAttachmentData(),
            'tagColumns' => $this->getTagColumns(),
            'tagDataSource' => $this->getTagDataSource(),
        ]);
    }
}
