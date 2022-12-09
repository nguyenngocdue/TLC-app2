<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\Workflow\Statuses;
use App\Models\Sub_project;

final class GetAllStatusSubProjects
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            return array_keys(Statuses::getFor('sub_project'));
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
