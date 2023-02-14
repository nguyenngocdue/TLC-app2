<?php

namespace App\View\Components\Renderer;

use App\Models\User;
use Illuminate\View\Component;

class Comment2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $value,
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
                "renderer" => "avatar-name",
                // "attributes" => ['title' => 'user', 'description' => 'position_rendered', 'avatar' => 'avatar', 'gray' => 'disabled']
            ],
            [
                "title" => 'Comment',
                "dataIndex" => "comment",
            ],
            [
                "title" => 'Date',
                "dataIndex" => "date",
            ],
        ];
    }

    public function getTableDataSource()
    {
        $dataSource = $this->value;
        $result = [];
        foreach ($dataSource as $value) {
            $ownerId = $value['owner_id'];
            $model = User::find($ownerId);
            $result[] = [
                "user" => $model,
                "date" => date('d/m/Y H:m:s', strtotime($value['created_at'])),
                "comment" => $value['content'],
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
        return view('components.renderer.comment2', [
            'tableEditableColumns' => $this->getTableColumns(),
            'tableDataSource' => $this->getTableDataSource(),
        ]);
    }
}
