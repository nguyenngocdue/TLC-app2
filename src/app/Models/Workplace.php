<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Workplace extends Model
{
    use HasFactory,Searchable;
    protected $fillable = ["name", "description"];
    protected $primaryKey = 'id';
    protected $table = 'workplaces';

    public $eloquentParams = [
        "user" => ['belongsTo' , User::class, 'owner_id'],
    ];
    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1],$p[2]);
    }
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
