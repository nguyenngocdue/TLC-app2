<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Erp_vendor extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "reg_no",
        "address",
        "owner_id",
    ];

    // protected static $externalTable = 'TLC_PROD.dbo.TLC_LLC$Vendor$437dbf0e-84ff-417a-965d-ed2bb9650972';
}
