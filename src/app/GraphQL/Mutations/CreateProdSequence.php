<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_sequence;

final class CreateProdSequence
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $prodRun = Prod_sequence::create(
                [
                    'prod_order_id' => $args['prod_order_id'],
                    'prod_routing_link_id' => $args['prod_routing_link_id'],
                    'status' => $args['status'],
                    'owner_id' => $args['owner_id'],
                ]
            );
            $prodRun->transitionTo($args['status']);
            return $prodRun;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
