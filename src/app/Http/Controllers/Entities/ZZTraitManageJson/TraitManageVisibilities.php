<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Visibilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitManageVisibilities
{
    private function getColumnsVisibility()
    {
        $allStatuses = LibStatuses::getFor($this->type);
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
                "dataIndex" => 'hello',
                'renderer' => 'text',
                'editable' => true,
            ],
        ];
        $columns = array_map(fn ($i) => [
            'dataIndex' => $i['name'],
            'renderer' => 'text',
            'align' => 'center',
            'width' => 10,
            'title' => $i['title'],
        ], $allStatuses);
        return array_merge($firstColumns, $columns);
    }

    private function getDataSourceVisibility()
    {
        $dataInJson = Visibilities::getAllOf($this->type);
        return $dataInJson;
    }

    public function indexVisibility(Request $request)
    {
        return $this->indexObj($request, "dashboards.pages.manage-visibility", '_vsb');
    }

    public function storeVisibility(Request $request)
    {
        return $this->storeObj($request, Visibilities::class, '_vsb');
    }

    public function createVisibility(Request $request)
    {
        return $this->createObj($request, Visibilities::class);
    }
}
