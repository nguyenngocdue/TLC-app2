<?php

namespace App\View\Components\Print;

use App\Models\User;
use Illuminate\View\Component;

class Signature5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource,
        private $relationships
    ) {
        //
    }
    public function getTableColumns()
    {
        return [
            [
                "title" => 'User',
                "dataIndex" => "user",
                "renderer" => "avatar-user",
                // "attributes" => ['gray' => 'resigned'],
                // "attributes" => ['title' => 'user', 'description' => 'position_rendered', 'avatar' => 'avatar', 'gray' => 'disabled']
                "width" => 50,
            ],
            [
                "title" => 'Signature',
                "dataIndex" => "signature",
                "width" => 50,
            ],
            [
                "title" => 'Comment',
                "dataIndex" => "comment",
                "width" => 350,
            ],
            [
                "title" => 'Date',
                "dataIndex" => "date",
                "width" => 50,
            ],
        ];
    }

    public function getTableDataSource()
    {
        $result = [];
        foreach ($this->dataSource as $signature) {
            $ownerId = $signature['owner_id'];
            $model = User::find($ownerId);
            $comment = $signature['signature_comment'];
            $sign =$signature['value'];
            $result[] = [
                "user" => $model,
                "date" => date('d/m/Y H:m:s', strtotime($signature['created_at'])),
                "comment" => $comment,
                "signature" => $sign,
            ];
        }
        return $result;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->getTableDataSource();
        return view('components.print.signature5', [
            'tableEditableColumns' => $this->getTableColumns(),
            'tableDataSource' => $this->getTableDataSource(),
        ]);
    }
}
