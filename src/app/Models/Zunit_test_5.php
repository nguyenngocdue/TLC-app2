<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Zunit_test_5 extends Model
{
    use Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    protected $fillable = ["name", "attachment_1", "attachment_2", "attachment_3", "attachment_4", "attachment_5"];
    protected $primaryKey = "id";
    protected $table = "zunit_test_5s";
    public $menuTitle = "UT05 (Attachments)";

    public $eloquentParams = [
        "media" => ['morphMany', Attachment::class, 'mediable', 'object_type', 'object_id'],
    ];

    public function media()
    {
        // return $this->morphMany(Attachment::class, 'mediable', 'object_type', 'object_id');
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}
