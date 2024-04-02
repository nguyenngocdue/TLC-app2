<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class SignatureMultiple5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource,
        private $relationships
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $signatures = $this->dataSource['signature_multi'];
        $parent = $this->dataSource['parent'];
        $dataSource = $signatures;
        if(sizeof($signatures) != sizeof($parent)){
            $ids = $signatures->pluck('user_id')->toArray();
            $idsParent = $parent->pluck('id')->toArray();
            $a = array_diff($idsParent,$ids);
            foreach ($a as $id) {
                $dataSource[] = [
                    'id' => "tmp".$id,
                    'value' => '',
                    'user_id' => $id,
                    'updated_at' => '',
                ];
            }
        }
        return view('components.print.signature-multiple5',[
            'dataSource' => $dataSource
        ]);
    }
}
