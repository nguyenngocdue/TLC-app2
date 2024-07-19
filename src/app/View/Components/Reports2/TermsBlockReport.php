<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use App\Models\Field;
use App\Models\Term;

trait TermsBlockReport
{
    public function getTermName($id)
    {
        if ($id) {
            $aggName = Term::find($id)->name;
            return $aggName;
        }
        return null;
    }
}
