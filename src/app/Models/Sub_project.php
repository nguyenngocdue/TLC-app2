<?php

namespace App\Models;

use App\Http\Traits\HasStatus;
use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;


class Sub_project extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities, HasStatus;
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "status"];
    protected $primaryKey = 'id';
    protected $table = 'sub_projects';

    public $eloquentParams = [
        "prodOrders" => ['hasMany', Prod_order::class],
    ];
    public function prodOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
