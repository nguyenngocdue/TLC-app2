<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_page extends ModelExtended
{
    protected $fillable = [
        "id", "name", "report_id", "letter_head_id", "letter_footer_id",
        "is_landscape", "width", "height", "background",
        "is_stackable_letter_head", "is_full_width", "order_no", "owner_id"
    ];

    public static $eloquentParams = [
        "getParent" => ['belongsTo', Rp_report::class, 'report_id'],
        "getLetterHead" => ['belongsTo', Rp_letter_head::class, 'letter_head_id'],
        "getLetterFooter" => ['belongsTo', Rp_letter_footer::class, 'letter_footer_id'],

        "attachment_background" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLetterHead()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLetterFooter()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachment_background()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
