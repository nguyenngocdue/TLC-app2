<?php

namespace App\Http\Controllers\ComponentDemo;

trait TraitAttachmentData
{
    function getAttachmentData2()
    {
        $result = [
            "attachment_1" => [
                [
                    "id" => 1,
                    "url_thumbnail" => "avatars/01087-150x150.png",
                    "url_media" => "avatars/01087.png",
                    "filename" => 'file_name_123.xyz',
                    "category" => 1,
                ],
                [
                    "id" => 2,
                    "url_thumbnail" => "avatars/00080-150x150.jpg",
                    "url_media" => "avatars/00080.jpg",
                    "filename" => 'file_name_123.xyz',
                    "category" => 1,
                ],
                [
                    "id" => 3,
                    "url_thumbnail" => "avatars/01031-150x150.png",
                    "url_media" => "avatars/01031.png",
                    "filename" => 'file_name_123.xyz',
                    "category" => 1,
                ],
            ],
            "attachment_2" => [
                [
                    "id" => 1,
                    "url_thumbnail" => "avatars/01034-150x150.jpg",
                    "url_media" => "avatars/01034.jpg",
                    "filename" => 'file_name_123.xyz',
                    "category" => 2,
                ],
                [
                    "id" => 2,
                    "url_thumbnail" => "avatars/01163-150x150.jpg",
                    "url_media" => "avatars/01163.jpg",
                    "filename" => 'file_name_123.xyz',
                    "category" => 2,
                ],
                [
                    "id" => 3,
                    "url_thumbnail" => "avatars/01340-150x150.jpeg",
                    "url_media" => "avatars/01340.jpeg",
                    "filename" => 'file_name_123.xyz',
                    "category" => 2,
                ],
            ],
            "attachment_3" => [
                [
                    "id" => 1,
                    "url_thumbnail" => "avatars/01491-150x150.png",
                    "url_media" => "avatars/01491.png",
                    "filename" => 'file_name_123.xyz',
                    "category" => 3,
                ],
                [
                    "id" => 2,
                    "url_thumbnail" => "avatars/01021-150x150.jpg",
                    "url_media" => "avatars/01021.jpg",
                    "filename" => 'file_name_123.xyz',
                    "category" => 3,
                ],
                [
                    "id" => 3,
                    "url_thumbnail" => "avatars/00041-150x150.png",
                    "url_media" => "avatars/00041.png",
                    "filename" => 'file_name_123.xyz',
                    "category" => 3,
                ],
            ],
        ];

        return $result;
    }

    function getAttachmentData()
    {
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

        return $attachmentData;
    }
}
