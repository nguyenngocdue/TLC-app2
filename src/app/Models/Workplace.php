<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Workplace extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["name", "description"];
    protected $primaryKey = 'id';
    protected $table = 'workplaces';

    public $eloquentParams = [
        // "user" => ['belongsTo' , User::class, 'owner_id'],
        "user" => ['hasMany', User::class, 'workplace'],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];

        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function unitTestDropDown1s()
    {
        return $this->hasMany(ZunitTest1::class, 'dropdown1', 'id');
    }

    public function unitTestRadio1s()
    {
        return $this->hasMany(ZunitTest1::class, 'radio1');
    }
    public function unitTestCheckBox1()
    {
        return $this->belongsToMany(ZunitTest1::class, 'zunit_test_1s_workplaces_rel_1', 'zunit_test_1_id', 'workplace_id');
    }
    public function unitTestDropDown2()
    {
        return $this->belongsToMany(ZunitTest1::class, 'zunit_test_1s_workplaces_rel_2', 'zunit_test_1_id', 'workplace_id');
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
