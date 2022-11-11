<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\DB;

final class UsersUsing
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            return DB::table('users')->where('resigned', '0')->orderBy('id', 'DESC')->get();
        } catch (\Throwable $th) {
        }
    }
}
