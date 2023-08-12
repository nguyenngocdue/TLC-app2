<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use Illuminate\Support\Facades\Blade;

trait TraitTableRendererSameAsViewAll
{
    private function renderSameAsViewAll($props, $dataSource)
    {
        $renderer_view_all = $props['relationships']['renderer_view_all'];
        $renderer_view_all_param = $props['relationships']['renderer_view_all_param'];
        $renderer_view_all_unit = $props['relationships']['renderer_view_all_unit'];
        $slot = json_encode($dataSource->all());
        $tag = "x-renderer.$renderer_view_all";
        $output = "<$tag renderRaw=1 rendererParam='$renderer_view_all_param' rendererUnit='$renderer_view_all_unit'>$slot</$tag>";
        return Blade::render($output);
    }
}
