<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Zunit_test_2 extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = [];
    protected $table = "zunit_test_2s";
    public $menuTitle = "UT02 (Dropdown/Checkbox)";


    public $eloquentParams = [
        "workplaceDropDown1" => ['belongsTo', Workplace::class, 'Dropdown_Yes/No'],
        "workplaceRadio1" => ['belongsTo', Workplace::class, 'Radio_Yes/No'],
    ];
    public function workplaceDropDown1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function workplaceRadio1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function workplaceRel1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function workplaceRel2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}
