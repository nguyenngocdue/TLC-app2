<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZunitTest3 extends Model
{
    use HasFactory;
    protected $fillable = ["datetime1", "datetime2", "datetime3", "datetime4", "datetime5", "datetime6", "datetime7"];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_3';
}
