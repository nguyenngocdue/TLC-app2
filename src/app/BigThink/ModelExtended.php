<?php

namespace App\BigThink;

use App\Http\Traits\HasAttachments;
use App\Http\Traits\HasCheckbox;
use App\Http\Traits\HasComments;
use App\Http\Traits\HasStatus;
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

    use HasStatus;
    use HasCheckbox;
    use HasComments;
    use HasAttachments;

    use CheckPermissionEntities;
    use TraitMetaForChart;
    use TraitMenuTitle;
    use TraitMorphManyByFieldName;

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
