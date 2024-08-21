<?php

namespace App\View\Components\Reports2;

use Illuminate\View\Component;
use App\Models\Term;
use App\Utils\Support\HrefReport;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportBlockTable extends Component
{
    use TraitReportDataAndColumn;
    use TraitReportTermNames;

    public function __construct(
        private $reportId,
        private $block,
        private $rawTableDataSource,
        private $rawTableColumns,
        private $dataHeader,
    ) {}

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
            //Tofix: check if getParent if getParent()
            $column = $column->getParent();
        }
        $content = $this->createIconPosition($value, $column->row_icon, $column->row_icon_position);
        return $content;
    }

    private function paginateDataSource($dataSource, $hasPagination, $pageLimit)
    {
        $page = $_GET['page'] ?? 1;
        // Convert array to a collection

        //Tofix: check if any case that $dataSource is not a collection
        if (!($dataSource instanceof Collection)) {
            $dataSource = collect($dataSource);
        }
        if ($hasPagination) {
            $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $pageLimit), $dataSource->count(), $pageLimit, $page))
                //Tofix: check if this is necessary
                // ->appends(request()->query())
            ;
        }
        return $dataSource;
    }

    private function createTableDataSourceForRows($queriedData, $reducedKeyAndColumns, $block)
    {
        $hasPagination = ($y = $block->has_pagination) ? (bool)$y : false;
        $queriedData = $this->paginateDataSource($queriedData, $hasPagination, 10);
        // dd($queriedData);
        $result = collect();
        foreach ($queriedData as $k1 => $dataLine) {
            $re = (object)[];
            foreach ($dataLine as $k2 => $value) {
                if (array_key_exists($k2, $reducedKeyAndColumns)) {
                    $column = $reducedKeyAndColumns[$k2];
                    $dataHref = HrefReport::createDataHrefForRow($column, $dataLine);
                    $content = $this->createContentInRowCell($value, $column);
                    // Log::info($content);
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
        // dd($result);
        return $result;
    }


    public function render()
    {
        $block = $this->block;

        //Tofix: do not re-query
        $columns = $this->block->getLines()->get()->sortby('order_no');

        $dataIndexToRender = array_column($this->rawTableColumns, 'dataIndex');
        $reducedKeyAndCols = $this->createKeyColumns($columns, $dataIndexToRender);

        $newTableDataSource = $this->createTableDataSourceForRows($this->rawTableDataSource, $reducedKeyAndCols, $block);

        return view('components.reports2.report-block-table', [
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
