<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Ndc\SpatieCustom\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use Searchable;

    // public function toSearchableArray()
    // {
    //     return [
    //         'id' => $this->id,
    //         'name' => $this->name,
    //     ];
    // }
}
