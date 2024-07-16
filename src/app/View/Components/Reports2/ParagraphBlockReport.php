<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ParagraphBlockReport extends Component
{
    use TraitDataColumnReport;
    use TraitCreateSQLReport2;
    public function __construct(
        private $block = null,
    ) {
    }

    private function renderHtml($strHtml, $varsInHtml)
    {
        $html = $this->preg_match_all($strHtml, $varsInHtml);
        return $html;
    }
    public function render()
    {
        $block = $this->block;

        $varsInHtml = [
            "my_variable" => "\$my_variable",
            "img_path" => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/React-icon.svg/1150px-React-icon.svg.png',
            "background" => 'bg-blue-500',
            "action_type" => "store",
            "entity_name" => "sub_projects",
            "id_route" => 120
        ];

        $strHtml = $block->html_content;
        $htmlRender = $this->renderHtml($strHtml, $varsInHtml);

        return view('components.reports2.paragraph-block-report', [
            'name' => $block->name ?? '',
            'description' => $block->description ?? '',
            'htmlRender' => Blade::render($htmlRender)
        ]);
    }
}
