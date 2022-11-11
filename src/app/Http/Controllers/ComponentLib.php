<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class ComponentLib extends Controller
{
    public function index()
    {
        $tableColumns = [
            [
                "title" => 'ID', "dataIndex" => "id",
                "render" => "id",
                "align" => "center",
            ],
            [
                "title" => 'Client', "dataIndex" => "client",
                "render" => "avatar-name", "attributes" => ['title' => 'client', 'description' => 'amount', 'avatar' => 'avatar']
            ],
            [
                "title" => 'Amount', "dataIndex" => "amount",
            ],
            [
                "title" => 'Status', "dataIndex" => "status",
                "render" => "tag", "attributes" => ['color' => 'color']
            ],
            [
                "title" => 'Date', "dataIndex" => "date",
            ],
        ];

        $tableEditableColumns = [
            [
                "title" => 'ID',
                "dataIndex" => "id",
                "render" => "id",
            ],
            [
                "title" => 'Client',
                "dataIndex" => "client",
                "render" => "dropdown",
                "editable" => true,
                "dataSource" => [
                    ["title" => "", "value" => ""],
                    ["title" => "Hans", "value" => "Hans"],
                    ["title" => "Jolina", "value" => "Jolina"],
                    ["title" => "Sarah", "value" => "Sarah"],
                    ["title" => "Rulia", "value" => "Rulia"],
                    ["title" => "Dave", "value" => "Dave"],
                ],
            ],
            [
                "title" => 'Amount',
                "dataIndex" => "amount",
                "render" => "number",
                "editable" => true,
            ],
            [
                "title" => 'Status',
                "dataIndex" => "status",
                "render" => "dropdown",
                "editable" => true,
                "dataSource" => [
                    ["title" => "", "value" => "",],
                    ["title" => "Approved", "value" => "approved",],
                    ["title" => "Pending", "value" => "pending",],
                    ["title" => "Rejected", "value" => "rejected",],
                    ["title" => "Expired", "value" => "expired",],
                ],
            ],
            [
                "title" => 'Date',
                "dataIndex" => "date",
                "render" => "text",
                "editable" => true,
            ],
        ];

        $tableDataSource = [
            [
                "id" => 1001, "client" => "Empty Avatar", "amount" => 1213.45, "status" => "approved", "color" => "green", "date" => "26/10/2022",
                "rowDescription" => "This is an example of or rowDescription",
            ],
            [
                "avatar" => "https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ",
                "id" => 2002, "client" => "Hans", "amount" => 863.45, "status" => "abc", "date" => "06/10/2022",
            ],
            [
                "avatar" => "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&facepad=3&fit=facearea&s=707b9c33066bf8808c934c8ab394dff6",
                "id" => 3003, "client" => "Jolina", "amount" => 123.45, "status" => "pending", "color" => "orange", "date" => "07/10/2022",
            ],
            [
                "avatar" => "https://images.unsplash.com/photo-1551069613-1904dbdcda11?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ",
                "id" => 4004, "client" => "Sarah", "amount" => 456.45, "status" => "rejected", "color" => "red", "date" => "08/10/2022",
            ],
            [
                "avatar" => "https://images.unsplash.com/photo-1551006917-3b4c078c47c9?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ",
                "id" => 5005, "client" => "Rulia", "amount" => 789.45, "status" => "expired", "color" => "gray", "date" => "09/10/2022",
            ],
            [
                "avatar" => "https://images.unsplash.com/photo-1502720705749-871143f0e671?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&s=b8377ca9f985d80264279f277f3a67f5",
                "id" => 6006, "client" => "Dave", "amount" => 1011.45, "status" => "approved", "color" => "green", "date" => "16/10/2022",
            ],
        ];

        return view('componentLib')->with(compact('tableColumns', 'tableEditableColumns', 'tableDataSource'));
    }
}
