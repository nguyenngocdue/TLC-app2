<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;


class Prod_run_line extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["prod_run_id", "date", "start", "end", "status"];
    protected $primaryKey = 'id';
    protected $table = 'prod_run_lines';
    public $timestamps = true;
    // protected $with = [
    //     'users',
    //     'productionRun',
    // ];

    public $eloquentParams = [
        "users" => ['belongsToMany', User::class, 'prod_user_runs', 'prod_run_line_id', 'user_id'],
        "prodRun" => ['belongsTo', Prod_run::class, 'prod_run_id'],
    ];
    public function users()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('user_id');
    }

    public function prodRun()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
