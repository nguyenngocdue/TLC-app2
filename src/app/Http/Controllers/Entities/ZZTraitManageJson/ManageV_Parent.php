<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\HiddenProps;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\ReadOnlyProps;
use App\Utils\Support\Json\RequiredProps;
use App\Utils\Support\Json\VisibleProps;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

abstract class ManageV_Parent extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-v-parent";
    protected $excludedColumnsFromStoring = ['label', 'toggle'];
    protected $headerTop = 9;

    abstract protected function getColumnSource();

    protected function getColumns()
    {
        $firstColumns = [
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'column_name',
                'renderer' => 'read-only-text',
                'editable' => true,
            ],
            [
                "dataIndex" => 'label',
                'renderer' => 'read-only-text',
                'editable' => true,
                'align' => "right",
            ],
            [
                "dataIndex" => 'toggle',
                'width' => 10,
                'align' => 'center',
            ],
        ];

        $allStatuses = $this->getColumnSource();
        $columns = array_map(fn ($i) => [
            'dataIndex' => $i['name'],
            'renderer' => 'checkbox',
            'editable' => true,
            'align' => 'center',
            'width' => 10,
            'title' => $i['title'],
        ], $allStatuses);
        return array_merge($firstColumns, $columns);
    }

    protected function getDataSource()
    {
        $allProps = Props::getAllOf($this->type);
        $dataInJson = $this->jsonGetSet::getAllOf($this->type);
        // dump($dataInJson);
        $isNotVisibleProps = in_array($this->jsonGetSet, [HiddenProps::class, ReadOnlyProps::class, RequiredProps::class]);

        $allStatuses = array_keys($this->getColumnSource());
        $allStatusesStr = "[" . join(", ", array_map(fn ($i) => '"' . $i . '"', $allStatuses)) . "]";
        $allIdsStr = "[" . join(", ", array_keys(array_values($allProps))) . "]";
        $javascript = "const statuses = $allStatusesStr; const ids = $allIdsStr; ";
        $javascript .= "let k_horizon_mode = {}; let k_horizon_value = {};";
        $javascript .= "let k_vertical_mode = {}; let k_vertical_value = {};";
        echo "<script>$javascript</script>";
        // dump($allStatuses);
        if ($isNotVisibleProps) $visibleProps = VisibleProps::getAllOf($this->type);

        $result = [];
        $index = 0;
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
            $newItem['toggle'] = Blade::render("<x-renderer.button htmlType='button' size='xs' onClick='toggleVParent_Horizon($index)'>Tg</x-renderer.button>");
            $newItem['label'] =  $prop['label'];

            if ($isNotVisibleProps) {
                foreach ($allStatuses as $status) {
                    $visible =  (isset($visibleProps[$name]) && isset($visibleProps[$name][$status]) && $visibleProps[$name][$status] === 'true');
                    if (!$visible) $newItem[$status] = "invisible";
                }
            }

            $result[] = $newItem;
            $index++;
        }
        return $result;
    }

    protected function getDataHeader()
    {
        $allStatuses = array_keys($this->getColumnSource());
        $result = [];
        foreach ($allStatuses as $status) {
            $button = "<x-renderer.button htmlType='button' size='xs' value='xxx' onClick='toggleVParent_Vertical(\"$status\")'>Tg</x-renderer.button>";
            $result[$status] = Blade::render($button);
        }
        // Log::info($result);
        return $result;
    }
}
