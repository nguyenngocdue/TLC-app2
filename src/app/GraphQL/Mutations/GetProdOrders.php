<?php

namespace App\GraphQL\Mutations;

use App\Models\Sub_project;

final class GetProdOrders
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $prodOrders = Sub_project::find($args['id'])->getProdOrders()->orderBy('name', 'ASC')->get();
        return [
            'prod_orders' => $prodOrders,
            'sub_project_id' => $args['id']
        ];
    }
}
