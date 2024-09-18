<?php

namespace App\View\Components\Modals\ParentId7;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Rp_block;
use App\Models\Rp_report;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ParentId7TreeReportBlocks extends ParentId7Tree
{
    protected $jsFileName = 'parentId7TreeReportBlocks.js';

    private function createLinkedId($type, $id)
    {
        $route = route($type . ".edit", $id);
        $text = Str::makeId($id);
        return "(<a href='$route' target='_blank' class='text-blue-600'>$text</a>)";
    }

    private function makeAppLevel($entityTypes, &$result)
    {
        $entityTypes = array_unique($entityTypes);
        $xx = [];
        foreach ($entityTypes as $e) {
            $xx[$e] = $e ? LibApps::getFor($e)['title'] : "(No entity type)";
        }

        uasort($xx, fn($a, $b) => strcasecmp($a, $b));

        foreach ($xx as  $entityType => $title) {
            $result[] = [
                'id' => "entity_type_$entityType",
                'parent' => "#",
                'text' => $title,
                'state' => ['opened' => 0,],
                'data' => ['type' => 'entity'],
            ];
        }
    }

    private function makeOrphanLevel($blocksHaveParent, &$result)
    {
        $blocks = Rp_block::query()
            ->select('id', 'name')
            ->whereNotIn('id', $blocksHaveParent)
            ->orderBy('name')
            ->get();
        if (count($blocks) == 0) return;

        array_unshift($result, [
            'id' => "orphan_blocks",
            'parent' => "#",
            'text' => "(Orphan blocks)",
            'state' => ['opened' => 0,],
            'data' => ['type' => 'entity'],
        ]);

        foreach ($blocks as $block) {
            $result[] = [
                'id' => "block_id_$block->id",
                'parent' => "orphan_blocks",
                'text' => $block->name . " " . $this->createLinkedId('rp_blocks', $block->id),
                'data' => ['type' => 'block'],
            ];
        }
    }

    function getDataSource()
    {
        $result = [];

        $allReports = Rp_report::query()
            ->with([
                'getPages' => function ($query) {
                    $query->orderBy('name');
                },
                'getPages.getBlockDetails' => function ($query) {},
                'getPages.getBlockDetails.getBlock' => function ($query) {
                    $query->orderBy('name');
                },
                'getPages.getBlockDetails.getBlock.getRendererType' => function ($query) {
                    $query->orderBy('name');
                },

            ])
            ->orderBy('name') // Order reports by name
            ->get();

        $entityTypes = [];
        $blocksHaveParent = [];

        foreach ($allReports as $report) {
            $entityTypes[] = $report->entity_type;
            foreach ($report->getPages as $page) {
                $blockDetails = $page->getBlockDetails;
                $blockCount = 0;
                foreach ($blockDetails as $blockDetail) {
                    $block = $blockDetail->getBlock;
                    $blocksHaveParent[] = $block->id;
                    $blockCount++;
                    $result[] = [
                        'id' => "block_id_$block->id",
                        'parent' => "page_id_$page->id",
                        'text' => $block->name . " " . $this->createLinkedId('rp_blocks', $block->id),
                        'data' => ['type' => 'block'],
                    ];
                }
                $pageItem = [
                    'id' => "page_id_{$page->id}",
                    'parent' => "report_id_$report->id",
                    'text' => $page->name . " " . $this->createLinkedId('rp_pages', $page->id),
                    'state' => ['opened' => 1,],
                    'icon' => "fa-regular fa-file text-green-400",
                    'data' => ['type' => 'page'],
                ];
                if ($blockCount == 0) {
                    $pageItem['icon'] = "fa-regular fa-file-xmark text-pink-400";
                }
                $result[] = $pageItem;
            }
            $result[] = [
                'id' => "report_id_{$report->id}",
                'parent' => "entity_type_{$report->entity_type}",
                'text' => $report->name . " " . $this->createLinkedId('rp_reports', $report->id),
                'state' => ['opened' => 1,],
                'data' => ['type' => 'report'],
                'icon' => "text-blue-400 fa-regular fa-file-chart-column",
            ];
        }

        $this->makeAppLevel($entityTypes, $result);
        $this->makeOrphanLevel($blocksHaveParent, $result);

        return $result;
    }
}
