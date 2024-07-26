<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;
use Illuminate\View\Component;
use App\Models\Term;
use App\Utils\Support\HrefReport;
use App\Utils\Support\RegexReport;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class TableBlockReport extends Component
{
    use TraitDataColumnReport;
    use TermsBlockReport;

    public function __construct(
        private $reportId,
        private $block,
        private $rawTableDataSource,
        private $rawTableColumns,
        private $dataHeader,
    ) {
    }

    private function createKeyColumns($columns, $dataIndexToRender = [])
    {
        $result = [];
        foreach ($columns as  $column) {
            if (in_array($column->data_index, $dataIndexToRender)) {
                $result[$column->data_index] = $column;
            }
        }
        return $result;
    }

    private function createContentInRowCell($value, $column)
    {
        if ($column->parent_id) {
            $column = $column->getParent();
        }
        $content = $this->createIconPosition($value, $column->row_icon, $column->row_icon_position);
        return $content;
    }


    private function createTableDataSourceForRow($dataQuery, $keyAndReducedColumns)
    {
        $result = collect();
        foreach ($dataQuery as $k1 => $dataLine) {
            $re = (object)[];
            foreach ($dataLine as $k2 => $value) {
                if (array_key_exists($k2, $keyAndReducedColumns)) {
                    $column = $keyAndReducedColumns[$k2];
                    $dataHref = HrefReport::createDataHrefForRow($column, $dataLine);
                    $content = $this->createContentInRowCell($value, $column);
                    Log::info($content);

                    $newValue = (object)[
                        'value' => $content,
                        'cell_href' => $dataHref->first(),
                        'cell_class' => $column->row_cell_class,
                        'cell_div_class' => $column->row_cell_div_class,
                    ];
                    $re->$k2 = $newValue;
                }
            }
            $result->put($k1, $re);
        }
        return $result;
    }


    public function render()
    {
        $block = $this->block;
        $columns = $this->block->getLines()->get()->sortby('order_no');

        $dataIndexToRender = array_column($this->rawTableColumns, 'dataIndex');
        $keyAndColumnsReduced = $this->createKeyColumns($columns, $dataIndexToRender);

        $newTableDataSource = $this->createTableDataSourceForRow($this->rawTableDataSource, $keyAndColumnsReduced);

        return view('components.reports2.table-block-report', [
            'block' => $block,
            "name" => $block->name,
            "description" => $block->description,
            "tableDataSource" => $newTableDataSource,
            "tableColumns" =>  $this->rawTableColumns,
            "dataHeader" => $this->dataHeader,

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
