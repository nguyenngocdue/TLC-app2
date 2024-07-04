<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Models\Field;
use App\Utils\Support\Json\Properties;
use Illuminate\Support\Arr;
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
                'renderer' => 'number4',
                'editable' => true,
                'title' => 'Max File Size (in MB)',
                'properties' => ['placeholder' => 10],
            ],

            [
                'dataIndex' => 'hide_uploader',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'hide_upload_date',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            [
                'dataIndex' => 'max_file_count',
                'renderer' => 'number4',
                'editable' => true,
                'properties' => ['placeholder' => 10],
            ],
            [
                'dataIndex' => 'allowed_file_types',
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => [
                    // ['value' => '', 'title' => ''],
                    ['value' => 'only_images', 'title' => 'Only Images (JPG, JPEG, PNG, GIF)'],
                    ['value' => 'only_videos', 'title' => 'Only Videos (MP4)'],
                    ['value' => 'only_media', 'title' => 'Only Images and Videos'],
                    ['value' => 'only_non_media', 'title' => 'Only Non-Media (CSV, PDF, ZIP)'],
                    ['value' => 'all_supported', 'title' => 'All above formats'],
                ],
            ],
        ];
        $result['comment'] = [
            // [
            //     'dataIndex' => 'allowed_change_owner',
            //     'renderer' => 'checkbox',
            //     'editable' => true,
            //     'align' => 'center',
            // ],
            // [
            //     'dataIndex' => 'allowed_attachment',
            //     'renderer' => 'checkbox',
            //     'editable' => true,
            //     'align' => 'center',
            // ],
            [
                'dataIndex' => 'allowed_to_delete',
                'renderer' => 'checkbox',
                'editable' => true,
                'align' => 'center',
            ],
            // [
            //     'dataIndex' => 'force_comment_once',
            //     'renderer' => 'checkbox',
            //     'editable' => true,
            //     'align' => 'center',
            // ],
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
                'width' => 30,
            ],
            [
                'dataIndex' => 'name',
                'editable' => true,
                'renderer' => 'read-only-text4',
                'width' => 50,
            ],
            [
                "dataIndex" => "field_id",
                "title" => "Field ID",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => $columns,
                'sortBy' => 'name',
                "properties" => ["strFn" => 'same'],
                'width' => 150,
            ],
            [
                'dataIndex' => 'field_name',
                'editable' => true,
                'renderer' => 'read-only-text4',
            ],
        ];

        return [...$first_columns, ...$this->getPropertyColumns()];
    }

    protected function getDataSource()
    {
        $dataSource = Properties::getAllOf($this->type);
        // dump($dataSource);
        $fields = Field::all();
        $index = Arr::keyBy($fields, 'id');
        foreach ($dataSource as &$row) {
            // dump($index[$row['name']]);
            if (isset($row['field_id']) && $row['field_id']) {
                $name = $index[$row['field_id']]->name;
                if ($row['field_name'] != $name) {
                    $row['row_color'] = "blue";
                    $row['field_name'] = ["value" => $name, "title" => $row['field_name'] . " -> " . $name];
                }
            }
        }
        foreach (array_keys($dataSource) as $key) {
            $this->attachActionButtons($dataSource, $key, ['right_by_name']);
        }
        uasort($dataSource, fn ($a, $b) => $a['name'] <=> $b['name']);
        return array_values($dataSource);
    }
}
