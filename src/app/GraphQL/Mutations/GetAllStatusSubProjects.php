<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\Workflow\LibStatuses;
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
            return array_keys(LibStatuses::getFor('sub_project'));
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
