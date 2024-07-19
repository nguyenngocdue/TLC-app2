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
        private $rawTableDataSource,
        private $rawTableColumns,
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

    private function getTitleWithIcon($title, $icon, $iconPosition)
    {
        if ($iconPosition) {
            switch ($iconPosition) {
                case 611:
                    return $icon . ' ' . $title;
                case 612:
                    return $title . ' ' . $icon;
            }
        }
        return $title;
    }

    private function createTableDataSourceForRow($dataQuery, $keyAndReducedColumns)
    {
        $result = collect();

        foreach ($dataQuery as $k1 => $item) {
            $re = (object)[];
            foreach ($item as $k2 => $value) {
                if (array_key_exists($k2, $keyAndReducedColumns)) {
                    $column = $keyAndReducedColumns[$k2];
                    $title = $this->getTitleWithIcon($column->name, $column->icon, $column->row_icon_position);
                    $newValue = (object)[
                        'value' => $value,
                        'cell_href' => '',
                        'cell_class' => $column->row_cell_class,
                        'cell_title' => $title,
                        'cell_div_class' => $column->row_cell_div_class,
                    ];
                    $re->$k2 = $newValue;
                }
            }

            $result->put($k1, $re);
        }

        return $result;
    }

    private function editTableColumns($data)
    {
        $result = [];
        foreach ($data as $column) {
            $title = $this->getTitleWithIcon($column->name, $column->icon, $column->icon_position);
            $newValue = [
                'title' => $title,
                'dataIndex' => $column->data_index,
                'width' => $column->width,
                'align' => 'center',
                'cellClass' => $column->cell_class,
                'cellDivClass' => $column->cell_div_class,
            ];
            $result[] = $newValue;
        }
        return $result;
    }
    public function render()
    {
        $block = $this->block;

        $columns = $this->block->getLines()->get();
        $dataIndexToRender = array_column($this->rawTableColumns, 'dataIndex');
        $keyAndColumnsReduced = $this->createKeyColumns($columns, $dataIndexToRender);

        $newTableDataSource = $this->createTableDataSourceForRow($this->rawTableDataSource, $keyAndColumnsReduced);
        $editedTableColumns = $this->editTableColumns($keyAndColumnsReduced);

        return view('components.reports2.table-block-report', [
            'block' => $block,
            "name" => $block->name,
            "description" => $block->description,
            "tableDataSource" => $newTableDataSource,
            "tableColumns" =>  $editedTableColumns,
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
