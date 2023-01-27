<?php

namespace App\Http\Controllers\ComponentDemo;

trait TraitCommentData
{
    function getCommendData()
    {
        return [
            'id' => "12",
            'owner_id' => 632,
            'content' => 'How are you',
            'created_at' => '08/12/2022 09:20:02',
            'updated_at' => '08/12/2022 09:20:02'
        ];
    }
}
