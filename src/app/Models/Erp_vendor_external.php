<?php

namespace App\Models;

use App\BigThink\ModelErp;

class Erp_vendor_external extends ModelErp
{
    protected $connection = 'sqlsrv';
    protected $table = 'TLC_PROD.dbo.TLC_LLC$Vendor$437dbf0e-84ff-417a-965d-ed2bb9650972';
    public $timestamps = false;

    // protected $fillable = [
    //     "name",
    //     "description",
    //     "reg_no",
    //     "address",
    //     "owner_id",
    // ];
}
