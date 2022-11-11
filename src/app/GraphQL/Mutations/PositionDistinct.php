<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\DB;

final class PositionDistinct
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            return DB::table('users')->orderBy('position_rendered', 'DESC')->distinct()->get(['position_rendered']);
        } catch (\Throwable $th) {
        }
    }
}
