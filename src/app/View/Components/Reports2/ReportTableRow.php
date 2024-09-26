<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use App\Utils\Support\HrefReport;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ReportTableRow
{
    
    use TraitReportTableContent;
    use TraitReportFormatString;
    use TraitReportRowRendererType;

    private static $instance = null;
    private function _construct(){

    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ReportTableRow();
        }
        return self::$instance;
    }

    private function createContentInRowCell($value, $column)
    {
        if ($column->parent_id) {
            $column = $column->getParent;
        }
        $content = $this->createIconPosition($value, $column->row_icon, $column->row_icon_position);
        return $content;
    }

    public function createTableDataSourceForRows($queriedData, $configuredCols, $block)
    {
        foreach ($queriedData as $k1 => &$dataLine) {
            $re = (object)[];
            foreach ($dataLine as $k2 => $value) {
                if (array_key_exists($k2, $configuredCols)) {
                    $column = $configuredCols[$k2];
                    $entityType = $column->entity_type;
                    $href = ($x = $column->row_href_fn) ? $this->formatReportHref($x, $dataLine) : '';
                    $content = $this->createContentInRowCell($value, $column);
                    $cellClass = $column->row_cell_class;
                    $cellDivClass =  $column->row_cell_div_class;
                    $rowRenderer = $column->row_renderer;
                    
                    if($rowRenderer == $this->STATUS_ROW_RENDERER_ID) {
                       [$content, $cellClass] =  $this->makeStatusForEachRow($entityType, $value, $content);
                    }
                    elseif($rowRenderer == $this->ID_ROW_RENDERER_ID) {
                        [$content, $cellClass, $href] = $this->makeIdForEachRow($entityType, $value, $content);
                    }
                    elseif($rowRenderer == $this->ROW_RENDERER_LINK_ID && $href) $cellClass = 'text-blue-600';
                    elseif($rowRenderer == $this->ROW_RENDERER_DATETIME_ID) {
                        $timeZoneNumber = User::find(CurrentUser::id())->time_zone;
                        $content = DateReport::convertToTimezone($value, $timeZoneNumber);
                    }

                    $newValue = (object)[
                        'original_value' => $value, // to export excel
                        'value' => $content,
                        'cell_href' => $href,
                        'cell_class' => $cellClass,
                        'cell_div_class' => $cellDivClass,
                    ];
                    $re->$k2 = $newValue;
                }
                elseif ($block->is_transformed_data) {
                    $re->$k2 = $value;
                }
            }
            $dataLine = $re;
            $queriedData->put($k1, $dataLine);
        }
        // dd($queriedData);
        return $queriedData;
    }
}
