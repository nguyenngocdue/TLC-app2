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
    protected $storingBlackList = ['label', 'toggle'];
    protected $headerTop = 24; //<<0..10, 11,12,14,16,20,24,28,32,36,40

    protected $showToggleColumn = true;
    protected $showToggleRow = true;

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
        if ($this->showToggleColumn) $firstColumns[] =    [
            "dataIndex" => 'toggle',
            'width' => 10,
            'align' => 'center',
        ];

        $allStatuses = $this->getColumnSource();
        $columns = array_map(fn($i) => [
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
            if ($this->showToggleColumn) $newItem['toggle'] = Blade::render("<x-renderer.button size='xs' onClick='toggleVParent_Horizon($index)'>Tg</x-renderer.button>");
            $newItem['label'] =  $prop['label'];

            if ($isNotVisibleProps) {
                foreach ($allStatuses as $status) {
                    $visible =  (isset($visibleProps[$name]) && isset($visibleProps[$name][$status]) && $visibleProps[$name][$status] === 'true');
                    if (!$visible) $newItem[$status] = "DO_NOT_RENDER";
                }
            }

            if (isset($prop['column_type']) && in_array($prop['column_type'], ['static_heading', 'static_control'])) $newItem['row_color'] = "amber";
            $result[] = $newItem;
            $index++;
        }
        return $result;
    }

    protected function getDataHeader()
    {
        if (!$this->showToggleRow) return null;
        $allStatuses = array_keys($this->getColumnSource());
        $result = [];
        foreach ($allStatuses as $status) {
            $button = "<x-renderer.button size='xs' value='xxx123' onClick=\"toggleVParent_Vertical('$status')\">Tg</x-renderer.button>";
            $result[$status] = Blade::render($button);
        }
        // Log::info($result);
        return $result;
    }

    protected function getMoreJS()
    {
        $allProps = Props::getAllOf($this->type);
        $allIdsStr = "[" . join(", ", array_keys(array_values($allProps))) . "]";
        $javascript = "const ids = $allIdsStr;";
        return "<script>$javascript</script>";
    }
}
