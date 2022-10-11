<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Zunit_test_3 extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["datetime1", "datetime2", "datetime3", "datetime4", "datetime5", "datetime6", "datetime7"];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_3s';
    public $eloquentParams = [];
}
