<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;
use Illuminate\View\Component;

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

    private function createIconPosition($content, $iconPosition, $icon)
    {
        if ($iconPosition) {
            switch ($iconPosition) {
                case 'Left':
                    return $icon . ' ' . $content;
                case 'Right':
                    return $content . ' ' . $icon;
            }
        }
        return $content;
    }

    private function createContentInHeader($content, $icon, $iconPosition)
    {
        return $this->createIconPosition($content, $iconPosition, $icon);
    }

    private function createContentInRowCell($value, $column)
    {
        $rowIcon = $column->row_icon;
        $rowIconPosition = $this->getTermName($column->row_icon_position);
        $rowRenderer = $this->getTermName($column->row_renderer);
        switch ($rowRenderer) {
            case "Icon":
                $content = $rowIcon;
                break;
            default:
                $content = $this->createIconPosition($value, $rowIconPosition, $rowIcon);
                break;
        }
        return $content;
    }

    private function createTableDataSourceForRow($dataQuery, $keyAndReducedColumns)
    {
        $result = collect();
        foreach ($dataQuery as $k1 => $item) {
            $re = (object)[];
            foreach ($item as $k2 => $value) {
                if (array_key_exists($k2, $keyAndReducedColumns)) {
                    $column = $keyAndReducedColumns[$k2];
                    $content = $this->createContentInRowCell($value, $column);
                    $newValue = (object)[
                        'value' => $content,
                        'cell_href' => 'google.com.vn',
                        'cell_class' => $column->row_cell_class,
                        // 'cell_title' => $title,
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
            $title = $this->createContentInHeader($column->name, $column->icon, $column->icon_position);
            $aagFooter = $this->getTermName($column->agg_footer);
            $newValue = [
                'title' => $title,
                'dataIndex' => $column->data_index,
                'width' => $column->width,
                'align' => 'center',
                'footer' => $aagFooter,
                'colspan' => $column->col_span_second_header ?? null,
                // not yet to code
                'cell_class' => $column->cell_class,
                'cell_div_class' => $column->cell_div_class,
            ];
            $result[] = $newValue;
        }
        return $result;
    }

    public function render()
    {
        $block = $this->block;

        $columns = $this->block->getLines()->get()->sortby('order_no');
        $secondColumns = $this->block->get2ndHeaderLines()->get()->sortby('order_no');
        // dd($secondColumns);


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
