<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Prod_line extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    public $timestamps = false;
    protected $fillable = ["prod_run_id", "date", "start", "end", "status"];
    protected $primaryKey = 'id';
    protected $table = 'prod_lines';

    public $eloquentParams = [
        "users" => ['belongsToMany', User::class, 'prod_user_runs', 'prod_run_id', 'user_id'],
        "productionRun" => ['belongsTo', Prod_run::class],
    ];
    public function users()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('user_id');
    }

    public function productionRun()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }
}
