<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Zunit_test_5 extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["name", "attachment_1", "attachment_2"];
    protected $primaryKey = "id";
    protected $table = "zunit_test_5s";
    public $eloquentParams = [
        "media" => ['hasMany', Media::class, 'owner_id', 'id'],
    ];

    public function media()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
