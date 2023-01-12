<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class ComponentLib extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    public function index()
    {
        $tableColumns = [
            [
                "title" => 'ID', "dataIndex" => "id",
                "renderer" => "id",
                "align" => "center",
            ],
            [
                "title" => 'Client', "dataIndex" => "client",
                "renderer" => "avatar-name",
                "attributes" => ['title' => 'client', 'description' => 'amount', 'avatar' => 'avatar', 'gray' => 'disabled']
            ],
            [
                "title" => 'Logged In',
                "dataIndex" => "loggedIn",
                "renderer" => "toggle",
                "align" => "center",
            ],
            [
                "title" => 'Amount', "dataIndex" => "amount",
            ],
            [
                "title" => 'Status', "dataIndex" => "status",
                "renderer" => "tag", "attributes" => ['color' => 'color']
            ],
            [
                "title" => 'Date', "dataIndex" => "date",
            ],
        ];

        $tableEditableColumns = [
            [
                "title" => 'ID',
                "dataIndex" => "id",
                "renderer" => "id",
            ],
            [
                "title" => 'Client',
                "dataIndex" => "client",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => ["", "Hans", "Helen", "sarah", "Sandy", "Eva", "Travis"],
            ],
            [
                "title" => 'Logged In',
                "dataIndex" => "loggedIn",
                "renderer" => "toggle",
                "align" => "center",
                // "editable" => true,
            ],
            [
                "title" => 'Amount',
                "dataIndex" => "amount",
                "renderer" => "number",
                "editable" => true,
            ],
            [
                "title" => 'Status',
                "dataIndex" => "status",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => ["", "approved", "pending", "rejected", "expired"],
            ],
            [
                "title" => 'Date',
                "dataIndex" => "date",
                "renderer" => "text",
                "editable" => true,
            ],
        ];

        $tableDataSource = [
            [
                "id" => 1001, "client" => "Empty Avatar", "amount" => 1213.45, "status" => "approved", "color" => "green", "date" => "26/10/2022",
                "rowDescription" => "This is an example of a rowDescription",
                "loggedIn" => true,
            ],
            [
                "avatar" => "/images/hans.jpeg",
                "id" => 2002, "client" => "Hans", "amount" => 863.45, "status" => "abc", "date" => "06/10/2022",
                "loggedIn" => false,
            ],
            [
                "avatar" => "/images/helen.jpeg",
                "id" => 3003, "client" => "Helen", "amount" => 123.45, "status" => "pending", "color" => "orange", "date" => "07/10/2022",
                "loggedIn" => true,
                "disabled" => true,
            ],
            [
                "avatar" => "/images/sarah.jpeg",
                "id" => 4004, "client" => "sarah", "amount" => 456.45, "status" => "rejected", "color" => "red", "date" => "08/10/2022",
                "loggedIn" => false,
            ],
            [
                "avatar" => "/images/Sandy.jpeg",
                "id" => 5005, "client" => "Sandy", "amount" => 789.45, "status" => "expired", "color" => "gray", "date" => "09/10/2022",
                "loggedIn" => 1,
            ],
            [
                "avatar" => "/images/avatar.jpg",
                "id" => 6006, "client" => "Eva", "amount" => 1011.45, "status" => "approved", "color" => "green", "date" => "16/10/2022",
                "loggedIn" => 0,
            ],
            [
                "avatar" => "/images/Travis.jpeg",
                "id" => 6006, "client" => "Travis", "amount" => 1011.45, "status" => "approved", "color" => "green", "date" => "16/10/2022",
                "loggedIn" => 0,
            ],
        ];

        $tagColumns = [
            ["title" => '100', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex100']],
            ["title" => '200', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex200']],
            ["title" => '300', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex300']],
            ["title" => '400', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex400']],
            ["title" => '500', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex500']],
            ["title" => '600', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex600']],
            ["title" => '700', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex700']],
            ["title" => '800', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex800']],
            ["title" => '900', "dataIndex" => "color", "renderer" => "tag", "align" => "center", "attributes" => ['color' => 'color', 'colorIndex' => 'colorIndex900']],
        ];
        $tagDataSource = [];
        $tagTemplate = [
            'colorIndex100' => 100,
            'colorIndex200' => 200,
            'colorIndex300' => 300,
            'colorIndex400' => 400,
            'colorIndex500' => 500,
            'colorIndex600' => 600,
            'colorIndex700' => 700,
            'colorIndex800' => 800,
            'colorIndex900' => 900,
        ];

        foreach ([
            'slate', 'zinc', 'neutral', 'stone', 'amber', 'yellow', 'lime', 'emerald', 'teal', 'cyan', 'sky',
            'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose', 'green', 'orange', 'red', 'gray',
        ] as $color) {
            $tmp = $tagTemplate;
            $tmp['color'] = $color;
            $tagDataSource[] = $tmp;
        }

        $gridDataSource = array_map(fn ($item) => [
            'name' => $item['client'],
            'avatar' => $item['avatar'] ?? null,
            'position_rendered' => $item['amount'],
            'gray' => $item['disabled'] ?? false,
        ], $tableDataSource);


        $dataComment = [
            'id' => "12",
            'owner_id' => 632,
            'content' => 'How are you',
            'created_at' => '08/12/2022 09:20:02',
            'updated_at' => '08/12/2022 09:20:02'
        ];

        $attachmentData = [
            "comment_1" => [
                [
                    "id" => 315,
                    "url_thumbnail" => "avatars/01087-150x150.png",
                    "url_media" => "avatars/01087.png",
                    "url_folder" => "avatars",
                    "filename" => "01087.png",
                    "extension" => "png",
                    "owner_id" => 1,
                    "object_id" => 157,
                    "object_type" => "App\Models\Comment",
                    "category" => 9,
                    "created_at" => "2022-12-14T06:46:48.000000Z",
                    "updated_at" => "2022-12-14T06:46:48.000000Z",
                ],
                [
                    "id" => 316,
                    "url_thumbnail" => "avatars/00080-150x150.jpg",
                    "url_media" => "avatars/00080.jpg",
                    "url_folder" => "avatars",
                    "filename" => "00080.jpg",
                    "extension" => "jpg",
                    "owner_id" => 1,
                    "object_id" => 157,
                    "object_type" => "App\Models\Comment",
                    "category" => 9,
                    "created_at" => "2022-12-14T06:46:48.000000Z",
                    "updated_at" => "2022-12-14T06:46:48.000000Z",
                ],
                [
                    "id" => 317,
                    "url_thumbnail" => "avatars/01031-150x150.png",
                    "url_media" => "avatars/01031.png",
                    "url_folder" => "avatars/",
                    "filename" => "01031.png",
                    "extension" => "png",
                    "owner_id" => 1,
                    "object_id" => 157,
                    "object_type" => "App\Models\Comment",
                    "category" => 9,
                    "created_at" => "2022-12-14T06:46:48.000000Z",
                    "updated_at" => "2022-12-14T06:46:48.000000Z",
                ]
            ],
            "comment_2" => [
                [
                    "id" => 318,
                    "url_thumbnail" => "dev-due001/2022/12/a4-150x150.jpeg",
                    "url_media" => "dev-due001/2022/12/a4.jpeg",
                    "url_folder" => "dev-due001/2022/12/",
                    "filename" => "a4.jpeg",
                    "extension" => "jpeg",
                    "owner_id" => 1,
                    "object_id" => 158,
                    "object_type" => "App\Models\Comment",
                    "category" => 10,
                    "created_at" => "2022-12-14T06:46:48.000000Z",
                    "updated_at" => "2022-12-14T06:46:48.000000Z",
                ]
            ]
        ];
        $attachmentData['attachment_2'] = $attachmentData['comment_1'];

        return view('componentLib', [
            'tableColumns' => $tableColumns,
            'tableEditableColumns' => $tableEditableColumns,
            'tableDataSource' => $tableDataSource,
            'gridDataSource' => $gridDataSource,
            'dataComment' => $dataComment,
            'attachmentData' => $attachmentData,
            'tagColumns' => $tagColumns,
            'tagDataSource' => $tagDataSource,
        ]);
    }
}
