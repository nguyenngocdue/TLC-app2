<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZunitTest1 extends Model
{
    use HasFactory, CheckPermissionEntities;
    protected $fillable = ["text1", "text2", "dropdown1", "radio1", "boolean1"];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_1s';

    public function workplaceDropDown1()
    {
        return $this->belongsTo(Workplace::class, 'dropdown1');
    }
    public function workplaceRadio1()
    {
        return $this->belongsTo(Workplace::class, 'radio1');
    }
    public function workplaceRel1()
    {
        return $this->belongsToMany(Workplace::class, 'zunit_test_1s_workplaces_rel_1', 'zunit_test_1_id', 'workplace_id');
    }
    public function workplaceRel2()
    {
        return $this->belongsToMany(Workplace::class, 'zunit_test_1s_workplaces_rel_2', 'zunit_test_1_id', 'workplace_id');
    }
}
