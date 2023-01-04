<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_5 extends ModelExtended
{
    protected $fillable = ["name", "attachment_1", "attachment_2", "attachment_3", "attachment_4", "attachment_5"];
    protected $primaryKey = "id";
    protected $table = "zunit_test_5s";
    public $menuTitle = "UT05 (Attachments)";

    public $eloquentParams = [
        "attachment" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function attachment()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}
