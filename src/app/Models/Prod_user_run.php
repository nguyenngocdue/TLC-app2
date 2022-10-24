<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Prod_user_run extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    public $timestamps = true;
    protected $fillable = ["prod_line_id", "user_id"];
    protected $primaryKey = 'id';
    protected $table = 'prod_user_runs';
}
