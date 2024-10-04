<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Facades\Blade;

trait TraitReportRendererType
{
    private function getRendererType($type, $entityType, $targetValue, $href)
    {
        $statuses = isset($entityType) ? LibStatuses::getFor($entityType) : '';
        $statusData = $statuses[$targetValue] ?? [];
        if (!$statusData) return [];
        $cellTooltip = 'Open this document (' . $statusData['title'] . ')';
        $cellTitle = $statusData['title'];
        $cellDivClass = '';
        switch ($type) {
            case 'tag':
            case $this->TAG_ROW_RENDERER_ID:
                $cellClass = 'text-' . $statusData['text_color'];
                $content = Blade::render("<x-renderer.status>" . $targetValue . "</x-renderer.status>");
                break;

            case 'tag_icon':
            case $this->TAG_ICON_ROW_RENDERER_ID:
                $icon = $statusData['icon'];
                $content = Blade::render(
                    "<x-renderer.status-icon href='{$href}', tooltip='{$cellTooltip}' title='{$cellTitle}'>{$icon}</x-renderer.status-icon"
                );
                $cellClass = 'bg-' . $statusData['bg_color'] . ' text-center';
                $cellDivClass = 'text-' . $statusData['text_color'];
                break;
            default:
                // No renderer type has been selected
                break;
        }
        return [$content, $cellClass, $cellDivClass, $cellTooltip];
    }

}
