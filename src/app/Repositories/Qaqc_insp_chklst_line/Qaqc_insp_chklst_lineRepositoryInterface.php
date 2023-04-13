<?php

namespace App\Repositories\Qaqc_insp_chklst_line;

use App\Repositories\RepositoryInterface;

interface Qaqc_insp_chklst_lineRepositoryInterface extends RepositoryInterface
{
    public function softDeletes($id);
    public function restore($id);
    public function trash($request);
}
