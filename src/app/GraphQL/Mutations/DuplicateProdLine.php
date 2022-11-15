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
            $userIds = [];
            foreach ($prodLine->users as $user) {
                array_push($userIds, $user->id);
            }
            if (!empty($userIds)) {
                $newProdLine->users()->attach($userIds);
            }
            return [
                'id' => $newProdLine->id,
                'status' => 'Duplicate Production Run Line Successfully.',
            ];
        } catch (\Throwable $th) {
            return [
                'status' => $th
            ];
        }
    }
}
