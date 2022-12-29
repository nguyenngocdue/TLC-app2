<?php

namespace App\BigThink;

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
    use MetaForChart;

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
}
