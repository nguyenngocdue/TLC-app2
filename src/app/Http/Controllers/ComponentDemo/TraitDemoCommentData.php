<?php

namespace App\Http\Controllers\ComponentDemo;

trait TraitDemoCommentData
{
    function makeComment($index)
    {
        return [
            'mine' => true,
            'comment_line_id' => 789,
            'owner_id' => [
                'display_name' => 'A member name',
                'value' => 1,
                'avatar' => '/images/helen.jpeg',
            ],
            'category_name' => [
                'name' => "comments[$index][category_name]",
                'value' => 'comment_1'
            ],
            'position_rendered' => [
                'value' => 'a position'
            ],
            'created_at' => [
                'value' => '2022-01-01 00:00:00'
            ],
            'id' => [
                'name' => "comments[$index][id]",
                'value' => 1,
            ],
            'toBeDeleted' => [
                'name' => "comments[$index][toBeDeleted]",
            ],
            'content' => [
                'name' => "comments[$index][content]",
                'value' => 'The comment',
            ],
        ];
    }
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
