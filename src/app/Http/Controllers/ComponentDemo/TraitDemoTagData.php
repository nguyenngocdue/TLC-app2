<?php

namespace App\Http\Controllers\ComponentDemo;

trait TraitDemoTagData
{
    function getTagColumns()
    {
        return [
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
    }

    function getTagDataSource()
    {
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
        return $tagDataSource;
    }
}
