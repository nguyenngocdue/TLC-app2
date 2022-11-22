<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\ManageStatusDocType;
use App\Http\Services\ReadingFileService;


class StatusDocType extends ManageStatusDocType
{
    protected $title = "Manage Doc Status Type";
    protected $w_file_path = "master/manage_status/doc_status.json";
    protected $r_file_path = "master/status_lib/all_statuses.json";

    public function getType()
    {
        return "workflow";
    }
}
