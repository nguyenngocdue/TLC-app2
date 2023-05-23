<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_run;
use Carbon\Carbon;

final class DuplicateProdRun
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $dt = Carbon::now();
            $prodLine = Prod_run::find($args['id']);
            $newProdLine = $prodLine->replicate();
            $newProdLine->date = $dt->format('Y-m-d');
            $newProdLine->start = $dt->format('H:i:s');
            $newProdLine->end = null;
            $newProdLine->save();
            $userIds = [];
            foreach ($prodLine->getUsers as $user) {
                array_push($userIds, $user->id);
            }
            if (!empty($userIds)) {
                $newProdLine->getUsers()->attach($userIds);
            }
            return [
                'id' => $newProdLine->id,
                'status' => 'Duplicate Production Run Successfully.',
            ];
        } catch (\Throwable $th) {
            return [
                'id' => null,
                'status' => $th
            ];
        }
    }
}
