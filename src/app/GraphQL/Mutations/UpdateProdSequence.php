<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_sequence;

final class UpdateProdSequence
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $prodSequence = Prod_sequence::find($args['id']);
            $prodSequence->status = $args['status'] ?? null;
            $prodSequence->total_hours = $args['total_hours'] ?? null;
            $prodSequence->total_man_hours = $args['total_man_hours'] ?? null;
            $prodSequence->save();
            $prodSequence->transitionTo($args['status']);
            return $prodSequence;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
