<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_claim extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "owner_id",
    ];

    // public static $statusless = true;

    public static $eloquentParams = [
        "getClaimableLines" => ['morphMany', Fin_expense_claim_line::class, 'claimable', 'claimable_type', 'claimable_id'],
    ];

    public function getClaimableLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
