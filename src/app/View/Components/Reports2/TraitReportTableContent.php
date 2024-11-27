<?php

namespace App\View\Components\Reports2;


trait TraitReportTableContent
{
    use TraitReportTermNames;
    use TraitReportFormatString;

    protected $EXPORT_TYPE_ID = 621;
    protected $ID_ROW_RENDERER_ID = 662;
    protected $ROW_RENDERER_DATETIME_ID = 663;
    protected $ROW_RENDERER_LINK_ID = 664;

    protected $TAG_ROW_RENDERER_ID = 661;
    protected $TAG_ICON_ROW_RENDERER_ID = 665;
    protected $PAGINATION_TYPE_ID = 622;

    protected $CHART_TYPE_ID = 642;
    protected $PARAGRAPH_TYPE_ID = 643;
    protected $DESCRIPTION_TYPE_ID = 644;

    public function createHeaderTitle($content, $icon, $iconPosition, $currentParams = [])
    {
        $rowIconPosition = $this->getIconName($iconPosition);
        if ($rowIconPosition) {
            switch ($rowIconPosition) {
                case 'Right':
                    return $content . ' ' . $icon;
                case 'Left':
                default:
                    return $icon . ' ' . $content;
            }
        }
        $parsedVariables = $this->parseVariables($content);
        if ($parsedVariables[0]) {
            $content = $this->formatReportHref($content, $currentParams);
        } 
        return $content;
    }
}
