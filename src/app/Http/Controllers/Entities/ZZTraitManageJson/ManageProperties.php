<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Models\Field;
use App\Utils\Support\Json\Properties;
use Illuminate\Support\Str;

class ManageProperties extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-property";
    protected $routeKey = "_ppt";
    protected $jsonGetSet = Properties::class;

    protected function getPropertyColumns()
    {
        $result['attachment'] = [
            [
                'dataIndex' => 'max_file_size',
                'renderer' => 'number',
                'editable' => true,
                'title' => 'Max File Size (in MB)',
            ],
            [
                'dataIndex' => 'max_file_count',
                'renderer' => 'number',
                'editable' => true,
            ],
            [
                'dataIndex' => 'allowed_file_types',
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => [
                    ['value' => '', 'title' => ''],
                    ['value' => 'only_images', 'title' => 'Only Images (JPG, JPEG, PNG, GIF, WEBB, SVG)'],
                    ['value' => 'only_videos', 'title' => 'Only Videos (MP4)'],
                    ['value' => 'only_media', 'title' => 'Only Images and Videos'],
                    ['value' => 'only_non_media', 'title' => 'Only Non-Media (CSV, PDF, ZIP)'],
                    ['value' => 'all_supported', 'title' => 'All above formats'],
                ],
            ],
        ];
        $result['comment'] = [
            [
                'dataIndex' => 'allowed_change_owner',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'allowed_attachment',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'allowed_delete',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
        ];

        return $result[$this->type];
    }

    protected function getColumns()
    {
        $fields = Field::all();
        // dump($fields);
        $columns = array_map(fn ($i) => [
            'value' => $i['id'],
            'name' => $i['name'],
            'title' =>  $i['name'] . " - (" . Str::makeId($i['id']) . ")",
        ], $fields->toArray());
        $columns = [['value' => '', 'name' => ''], ...$columns];
        $first_columns = [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                "dataIndex" => "name",
                "title" => "Field Name",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => $columns,
                'sortBy' => 'name',
                "properties" => ["strFn" => 'same'],
            ],
        ];

        return [...$first_columns, ...$this->getPropertyColumns()];
    }

    protected function getDataSource()
    {
        $dataSource = Properties::getAllOf($this->type);
        foreach (array_keys($dataSource) as $key) {
            $this->attachActionButtons($dataSource, $key, ['right_by_name']);
        }
        return $dataSource;
    }
}
