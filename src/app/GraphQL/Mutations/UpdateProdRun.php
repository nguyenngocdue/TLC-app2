<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_run;

final class UpdateProdRun
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $prodRun = Prod_run::find($args['id']);
            $prodRun->status = $args['status'] ?? null;
            $prodRun->total_hours = $args['total_hours'] ?? null;
            $prodRun->total_man_hours = $args['total_man_hours'] ?? null;
            $prodRun->save();
            $prodRun->transitionTo($args['status']);
            return $prodRun;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
