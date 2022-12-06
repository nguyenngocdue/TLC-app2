<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Zunit_test_7 extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = [];
    protected $table = "zunit_test_7s";

    public $eloquentParams = [];
}
