<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Ndc\SpatieCustom\Models\RoleSet as ModelsRoleSet;

class Role_set extends ModelsRoleSet
{
    use Searchable;
    public static $nameless = true;
    // public function getName()
    // {
    //     return $this->name;
    // }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
    public static function getTableName()
    {
        return (new static())->getTable();
    }
}
