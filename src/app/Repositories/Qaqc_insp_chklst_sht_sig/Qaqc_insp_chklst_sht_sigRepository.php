<?php

namespace App\Repositories\Qaqc_insp_chklst_sht_sig;

use App\Models\Signature;
use App\Repositories\BaseRepository;
// use App\Models\Qaqc_insp_chklst_sht_sig;

class Qaqc_insp_chklst_sht_sigRepository extends BaseRepository implements Qaqc_insp_chklst_sht_sigRepositoryInterface
{

    public function getModel()
    {
        return Signature::class;//Qaqc_insp_chklst_sht_sig::class;
    }
}
