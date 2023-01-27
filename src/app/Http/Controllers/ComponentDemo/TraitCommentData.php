<?php

namespace App\Http\Controllers\ComponentDemo;

trait TraitCommentData
{
    function getCommendData()
    {
        return [
            'id' => "1",
            'owner_id' => 1,
            'content' => 'How are you',
            'created_at' => '08/12/2022 09:20:02',
            'updated_at' => '08/12/2022 09:20:02'
        ];
    }
}
