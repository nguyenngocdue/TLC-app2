<?php

namespace App\Http\Controllers\Reports;

abstract class Report_ParentReport2Controller extends Report_Parent2Controller
{
    use TraitForwardModeReport;
    use TraitGenerateTableColumns;
}
