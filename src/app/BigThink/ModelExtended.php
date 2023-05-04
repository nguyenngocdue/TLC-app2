<?php

namespace App\BigThink;

use App\Utils\OptimisticLocking\TraitOptimisticLocking;
use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class ModelExtended extends Model
{
    use Searchable;
    use Notifiable;
    use HasFactory;
    use TraitOptimisticLocking;

    use HasStatus;
    use HasCheckbox;
    use HasComments;
    use HasAttachments;

    use CheckPermissionEntities;
    use TraitMetaForChart;
    use TraitMenuTitle;
    use TraitMorphManyByFieldName;

    use TraitModelExtended;

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

    function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getDeleteBy()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
