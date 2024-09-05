<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use App\Models\Field;
use App\Models\Term;

trait TraitReportTermNames
{
    private function getTermName($id)
    {
        if ($id) {
            $name = Term::find($id)->name;
            return $name;
        }
        return null;
    }

    public function getAggName($id)
    {
        return $this->getTermName($id);
    }

    public function getIconName($id)
    {
        return $this->getTermName($id);
    }
}
