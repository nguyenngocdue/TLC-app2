<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Sub_project_status extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["id", "name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'sub_project_statuses';
    // protected $with = [
    //     "subProjects",
    // ];

    public $eloquentParams = [
        "subProjects" => ['hasMany', Sub_project::class, 'sub_project_status_id'],
    ];
    public function subProjects()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
