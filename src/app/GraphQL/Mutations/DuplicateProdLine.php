<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_line;
use App\Models\Prod_user_run;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;

final class DuplicateProdLine
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $dt = Carbon::now();
            $prodLine = Prod_line::find($args['id']);
            $newProdLine = $prodLine->replicate();
            $newProdLine->date = $dt->format('Y-m-d');
            $newProdLine->start = $dt->format('H:i:s');
            $newProdLine->end = null;
            $newProdLine->status = $prodLine->status;
            $newProdLine->save();
            $prodUserRuns = Prod_user_run::where('prod_line_id', $args['id'])->get();
            foreach ($prodUserRuns as $prodUserRun) {
                $newProdUserRun = $prodUserRun->replicate();
                $newProdUserRun->prod_line_id = $newProdLine->id;
                $newProdUserRun->save();
            }
            return [
                'status' => 'Duplicate Production Run Line Successfully.'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => $th
            ];
        }
    }
}
