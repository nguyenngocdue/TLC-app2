<?php

namespace App\Http\Controllers\Workflow;

class ManageListenerActionsController extends AbstractManageLibController
{
    protected $title = "Manage Listener Actions";
    protected $libraryClass = LibListenerActions::class;
    protected $route = "manageListenerActions";

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
                'dataIndex' => 'triggers',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'listen_to_fields',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'listen_to_attrs',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'columns_to_set',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'attrs_to_compare',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'expression',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'ajax_response_attribute',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'ajax_form_attributes',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'ajax_item_attributes',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'ajax_default_values',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],

        ];
    }
}
