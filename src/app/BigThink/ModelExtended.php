<?php

namespace App\BigThink;

use App\Http\Traits\HasCheckbox;
use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class ModelExtended extends Model
{
    use Searchable;
    use Notifiable;
    use HasFactory;
    use CheckPermissionEntities;
    use TraitMetaForChart;
    use TraitMenuTitle;
    use HasCheckbox;

    public $eloquentParams = [];
    public $oracyParams = [];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'description' => $this->description,
            // 'slug' => $this->slug,
        ];
    }

    //This static function is useful when a model is needed to get table name but data also is empty
    public static function getTableName()
    {
        return (new static())->getTable();
    }
}
