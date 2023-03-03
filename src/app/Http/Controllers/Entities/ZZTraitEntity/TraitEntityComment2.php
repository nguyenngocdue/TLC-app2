<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

trait TraitEntityComment2
{
    function removeNullCommentOfNullId($dataSource)
    {
        $result = [];
        foreach ($dataSource as $line) {
            if (!is_null($line['id']) || !is_null($line['content'])) {
                $result[] = $line;
            }
        }
        return $result;
    }
}
