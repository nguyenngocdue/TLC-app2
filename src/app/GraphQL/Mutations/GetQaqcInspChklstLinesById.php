<?php

namespace App\GraphQL\Mutations;

use App\Models\Qaqc_insp_sheet;

final class GetQaqcInspChklstLinesById
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            // $qaqcInspChklstLines = Qaqc_insp_sheet::find($args['qaqc_insp_sheet_id'])
            //     ->getChklstLines->where('qaqc_insp_chklst_id', $args['qaqc_insp_chklst_id']);
            // return $qaqcInspChklstLines;
        } catch (\Throwable $th) {
            // return $qaqcInspChklstLines;
        }
    }
}
