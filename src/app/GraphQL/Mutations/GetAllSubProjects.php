<?php

namespace App\GraphQL\Mutations;

use App\Models\Sub_project;

final class GetAllSubProjects
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return Sub_project::orderBy('id', 'ASC')->get();
    }
}
