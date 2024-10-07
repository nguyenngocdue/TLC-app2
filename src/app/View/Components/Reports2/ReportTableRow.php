<?php

namespace App\View\Components\Reports2;

class ReportTableRow
{
    
    use TraitReportTableContent;
    use TraitReportFormatString;
    use TraitReportTransformedRowData;
    use TraitReportRendererType;

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
        // Data retrieved from SQL query
        foreach ($queriedData as $k1 => &$dataLine) {
            $re = (object)[];
            foreach ($dataLine as $k2 => $value) {
                if (array_key_exists($k2, $configuredCols)) {
                    $column = $configuredCols[$k2];
                    $entityType = $column->entity_type;
                    $href = ($x = $column->row_href_fn) ? $x: '';
                    $content = $this->createContentInRowCell($value, $column);
                    $cellClass = $column->row_cell_class;
                    $cellDivClass =  $column->row_cell_div_class;
                    $rowRenderer = $column->row_renderer;
                    $cellTitle = '';
                    
                    switch ($rowRenderer) {
                        case $this->TAG_ROW_RENDERER_ID: // Render Status
                        case $this->TAG_ICON_ROW_RENDERER_ID:
                            [$content, $cellClass, $cellDivClass, $cellTitle] = $this->getRendererType($rowRenderer, $entityType, $content, $href);
                            break;
                    
                        case $this->ID_ROW_RENDERER_ID: // Render ID
                            [$content, $cellClass, $href] = $this->makeIdForEachRow($entityType, $value, $content);
                            break;
                    
                        case $this->ROW_RENDERER_LINK_ID:
                            if ($href) {
                                $cellClass = 'text-blue-600';
                            }
                            break;
                    
                        case $this->ROW_RENDERER_DATETIME_ID:
                            // $content = DateFormat::getValueDatetimeByCurrentUser($value);
                            break;
                    
                        default:
                            // Handle other cases or do nothing
                            break;
                    }
                    $newValue = $this->makeCellValue($value,$value, $content, $cellClass,$href, $cellDivClass, $cellTitle);
                    // $newValue = (object)[
                    //     'original_value' => $value, // to export excel
                    //     'value' => $content,
                    //     'cell_href' => $href,
                    //     'cell_class' => $cellClass,
                    //     'cell_div_class' => $cellDivClass,
                    // ];
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
