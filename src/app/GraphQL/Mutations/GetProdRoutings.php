<?php

namespace App\GraphQL\Mutations;

use App\Models\Prod_discipline;
use App\Models\Prod_order;

final class GetProdRoutings
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $subProjectId = $args['id1'];
        $prodOrderId = $args['id2'];
        $prodRoutings = Prod_order::find($args['id2'])->routing;
        $prodRoutingLinks = $prodRoutings->routingLinks;
        $prodDisciplines = Prod_discipline::all();
        return [
            'sub_project_id' => $subProjectId,
            'prod_order_id' => $prodOrderId,
            'prod_routing_links' => $prodRoutingLinks,
            'prod_disciplines' => $prodDisciplines
        ];
    }
}
