<?php

namespace App\Http\Controllers\Workflow;

class ManageApisController extends AbstractManageLibController
{
    protected $title = "Manage Apis";
    protected $libraryClass = LibApis::class;
    protected $route = "manageApis";
    protected $groupByLength = 2;

    protected function getColumns()
    {
        return [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                'dataIndex' => "name",
                "renderer"  => 'read-only-text4',
                'editable' => true,
            ],
            [
                'dataIndex' => 'createNewShort',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'updateShortSingle',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'storeEmpty_and_updateShort',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'cloneTemplate_and_updateShort',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'changeStatusMultiple',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'searchable',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            // [
            //     'dataIndex' => 'getLines',
            //     'renderer' => 'checkbox',
            //     'editable' => true,
            //     'align' => 'center',
            // ],
            [
                'dataIndex' => 'renderTableForPopupModals',
                'title' => 'Render Table for popup modals',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
        ];
    }
}
