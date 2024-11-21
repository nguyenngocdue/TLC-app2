<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibRoleSets;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Role_set;
use App\Utils\Support\Json\Capabilities;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ManageCapabilities extends Manage_Parent
{
    protected $viewName = "dashboards.pages.manage-capability";
    protected $routeKey = "_cpb";
    protected $jsonGetSet = Capabilities::class;
    protected $headerTop = 20 * 16;

    protected $showToggleColumn = true;

    protected function getColumns()
    {
        $firstColumn = [
            [
                "dataIndex" => 'name',
                'renderer' => 'read-only-text4',
                'editable' => true,
                'align' => 'center',
                'title' => 'Name',
            ],
            [
                "dataIndex" => 'name_rendered',
                'renderer' => 'text',
                'align' => 'right',
            ],
        ];
        if ($this->showToggleColumn) $firstColumn[] =    [
            "dataIndex" => 'toggle',
            'width' => 10,
            'align' => 'center',
        ];

        $allStatuses = LibStatuses::getFor($this->type);
        // dump($allStatuses);
        $columns = array_map(fn($i) => [
            "dataIndex" => $i['name'],
            'renderer' => 'checkbox',
            'editable' => true,
            'align' => 'center',
            'title' => $i['title'],
            'width' => 100,
        ], $allStatuses);
        $columns = array_merge($firstColumn, $columns);
        // dump($columns);
        return $columns;
    }

    protected function getDataHeader()
    {
        $result = [];
        $allStatuses = array_keys(LibStatuses::getFor($this->type));
        foreach ($allStatuses as $status) {
            $button = "<x-renderer.button size='xs' value='xxx123' onClick=\"toggleVParent_Vertical('$status', this)\">Tg</x-renderer.button>";
            $result[$status] = Blade::render($button);
        }
        // Log::info($result);
        return $result;
    }

    protected function getDataSource()
    {
        $allRoleSets = LibRoleSets::getAll();
        $dataInJson = Capabilities::getAllOf($this->type);
        // dump($dataInJson);
        $result = [];
        $index = 0;
        foreach ($allRoleSets as $status) {
            $name = $status['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = ['name' => $name];
            }
            if ($this->showToggleColumn) $newItem['toggle'] = Blade::render("<x-renderer.button size='xs' onClick='toggleVParent_Horizon($index)'>Tg</x-renderer.button>");
            $newItem['name_rendered'] = Str::appTitle($newItem['name']);
            $result[] = $newItem;
            $index++;
        }
        // dump($result);
        return $result;
    }

    protected function getMoreJS()
    {
        $dataInJson = Role_set::all()->toArray();
        $allIdsStr = "[" . join(", ", array_keys(array_values($dataInJson))) . "]";
        $javascript = "const ids = $allIdsStr;";
        return "<script>$javascript</script>";
    }
}
