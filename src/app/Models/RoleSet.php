<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Ndc\SpatieCustom\Models\RoleSet as ModelsRoleSet;

class RoleSet extends ModelsRoleSet
{
    use Searchable;
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
