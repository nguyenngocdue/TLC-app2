<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\IntermediateProps;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\Transitions;
use App\Utils\Support\Json\VisibleProps;
use Illuminate\Support\Facades\Log;

class ManageIntermediateProps extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-intermediate";
    protected $routeKey = "_itm";
    protected $jsonGetSet = IntermediateProps::class;
    protected $storingBlackList = ['label'];
    // protected $storingWhiteList = ['label', 'ball-in-court'];

    protected function getColumns()
    {
        $firstColumns = [
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text4',
                'editable' => true,
            ],
            [
                "dataIndex" => 'column_name',
                'renderer' => 'read-only-text4',
                'editable' => true,
            ],
            [
                "dataIndex" => 'label',
                'renderer' => 'read-only-text4',
                'editable' => true,
                'align' => "right",
            ],
        ];

        $allStatuses = LibStatuses::getFor($this->type);
        $columns = array_map(fn($i) => [
            'title' => $i['title'],
            'dataIndex' => $i['name'],
            'renderer' => 'dropdown',
            'editable' => true,
            'align' => 'center',
            'width' => 10,
        ], $allStatuses);
        return array_merge($firstColumns, $columns);
    }

    protected function getDataSource()
    {
        $allProps = Props::getAllOf($this->type);
        $dataInJson = $this->jsonGetSet::getAllOf($this->type);
        // dump($dataInJson);

        $visibleProps = VisibleProps::getAllOf($this->type);
        // dump($visibleProps);
        $transitionProps = Transitions::getAllOf($this->type);
        // dump($transitionProps);

        $result = [];
        foreach ($allProps as $prop) {
            $name = $prop['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = [
                    'name' => $name,
                    'column_name' => $prop['column_name'],
                ];
            }
            $newItem['label'] =  $prop['label'];
            if (isset($visibleProps[$name])) {
                $visibleProp = $visibleProps[$name];
                unset($visibleProp['name']);
                unset($visibleProp['column_name']);
                foreach ($visibleProp as $status => $true_or_false) {
                    if ($true_or_false !== 'true') {
                        $newItem[$status] = 'DO_NOT_RENDER';
                    } else {
                        $cbbDS = [];
                        $cbbDS[] = "";
                        foreach ($visibleProp as $k => $v) {
                            if ($v === 'true' && $k !== $status) {
                                if (isset($transitionProps[$status]) && $transitionProps[$status][$k] == 'true') {
                                    $cbbDS[] = $k;
                                }
                            }
                        }
                        $newItem[$status] = [
                            'value' => $newItem[$status] ?? "",
                            'cbbDS' => $cbbDS,
                        ];
                    }
                }
            }

            if (isset($prop['column_type']) && in_array($prop['column_type'], ['static_heading', 'static_control'])) {
                $newItem['row_color'] = "amber";
            }
            $result[] = $newItem;
        }
        return $result;
    }
}
