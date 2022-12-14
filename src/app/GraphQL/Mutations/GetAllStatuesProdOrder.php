<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\Workflow\Statuses;

final class GetAllStatuesProdOrder
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            return array_keys(Statuses::getFor('prod_order'));
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
