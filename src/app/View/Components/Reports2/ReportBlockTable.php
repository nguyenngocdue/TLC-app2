<?php

namespace App\View\Components\Reports2;

use App\Models\Rp_report;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Blade;
class ReportBlockTable extends Component
{
    use TraitReportQueriedData;
    use TraitReportTermNames;
    // use TraitReportCreateTableColumn;
    // use TraitReportCreateTableRow;
    
    use TraitReportTableContent;

    protected $reportType2 = 'report2';
    public function __construct(
        private $reportId,
        private $block,
        private $tableDataSource,
        private $headerCols,
        private $secondHeaderCols,
        private $currentParams,
        private $queriedData,
    ) {
    }

    private function getControls($typeId, $queriedData, $configuredCols){
        $rp =Rp_report::find($this->reportId);
        $reportId = $rp->id;
        $entityType = $rp->entity_type;
        $entityType2 = $this->reportType2;
        $routeExportExcel = route('rp_exportExcel');
        $currentParams = $this->currentParams;
        $pageLimit = $currentParams['per_page'] ?? 10;
        switch ($typeId) {
            case $this->PAGINATION_TYPE_ID:
                return Blade::render("<x-reports2.per-page-report2 
                                                    entityType='{$entityType}' 
                                                    reportType2='{$entityType2}' 
                                                    reportId='{$reportId}' 
                                                    pageLimit='{$pageLimit}'
                                    />");
            case $this->EXPORT_TYPE_ID:
                return Blade::render('<x-reports2.utility-report2 
                                            :route="$route" 
                                            :queriedData="$queriedData"
                                            :configuredCols="$configuredCols"
                                            />', [
                    'route' => $routeExportExcel,
                    'queriedData' => $queriedData,
                    'configuredCols' => $configuredCols
                ]);
            default:
                break;
        }
    }

    public function render()
    {
        $block = $this->block;
        $columns = $this->block->getLines->sortby('order_no');
        $headerCols = $this->headerCols;
        
        $dataIndexToRender = array_column($headerCols, 'dataIndex');
        
        $reportTableColumn = ReportTableColumn::getInstance();
        $configuredCols = $reportTableColumn->getConfiguredCols($columns, $dataIndexToRender);
        
        $reportTableRow = ReportTableRow::getInstance();
        $tableDataSource = $reportTableRow->createTableDataSourceForRows($this->tableDataSource, $configuredCols, $block);

        // render default table
        if(!$configuredCols) {
            $tableDataSource = $this->queriedData;
            $headerCols = $reportTableColumn->createColsWhenNotFoundRenderType();
        } 

        return view('components.reports2.report-block-table', [
            'block' => $block,
            "name" => $block->name,
            "description" => $block->description,
            "tableDataSource" => $tableDataSource,
            "tableColumns" =>  $headerCols,
            "secondHeaderCols" => $this->secondHeaderCols,

            "showNo" => $block->showNo,
            "tableTrueWidth" => $block->table_true_width,
            "maxHeight" => $block->max_h,
            "rotate45Width" => $block->rotate_45_width,
            "rotate45Height" => $block->rotate_45_height,
            "hasPagination" => $block->has_pagination,

            "topLeftControl" => $this->getControls($block->top_left_control, $this->queriedData, $configuredCols),
            "topCenterControl" => $this->getControls($block->top_center_control, $this->queriedData, $configuredCols),
            "topRightControl" =>  $this->getControls($block->top_right_control, $this->queriedData, $configuredCols),
            "bottomLeftControl" => $this->getControls($block->bottom_left_control, $this->queriedData, $configuredCols),
            "bottomCenterControl" => $this->getControls($block->bottom_center_control, $this->queriedData, $configuredCols),
            "bottomRightControl" => $this->getControls($block->bottom_right_control, $this->queriedData, $configuredCols),
        ]);
    }
}
