<?php

namespace App\View\Components\Renderer\ViewAllTreeType;

class PpProcedurePolicy extends ViewAllTypeTreeExplorer
{
    protected function getApiRoute()
    {
        return route("pp_procedure_policy_tree_explorer");
    }

    protected function getTree()
    {
        return [
            "id" => 1,
            "text" => "Hello"
        ];
    }
}
