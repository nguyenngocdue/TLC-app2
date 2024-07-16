<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;
use Illuminate\View\Component;

class TableBlockReport extends Component
{
    use TraitDataColumnReport;
    public function __construct(
        private $reportId,
        private $block,
        private $tableDataSource,
        private $tableColumns,
    ) {
    }

    public function render()
    {
        $block = $this->block;
        return view('components.reports2.table-block-report', [
            "name" => $block->name,
            "description" => $block->description,
            "tableDataSource" => $this->tableDataSource,
            "tableColumns" =>  $this->tableColumns,
            "showNo" => $block->showNo,
            "tableTrueWidth" => $block->table_true_width,
            "maxHeight" => $block->max_h,
            "rotate45Width" => $block->rotate_45_width,
            "rotate45Height" => $block->rotate_45_height,
            "hasPagination" => $block->has_pagination,

            "topLeftControl" => $block->top_left_control,
            "topCenterControl" => $block->top_center_control,
            "topRightControl" => $block->top_right_control,
            "bottomLeftControl" => $block->bottom_left_control,
            "bottomCenterControl" => $block->bottom_center_control,
            "bottomRightControl" => $block->bottom_right_control,
        ]);
    }
}
