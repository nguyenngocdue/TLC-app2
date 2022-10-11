<?php

namespace App\View\Components\Controls;

use App\Http\Services\ReadingFileService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    private $id;
    private $colName;
    public function __construct($id, ReadingFileService $readingFileService, $colName)
    {
        $this->id = $id;
        $this->readingFileService = $readingFileService;
        $this->colName = $colName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        $storage_path = storage_path('/json/entities/user/relationships.json');
        $relationship = json_decode(file_get_contents($storage_path), true);

        // $column_name = $relationship->{'_user'}['column_name'];
        $column_name = $relationship["_getWorkplace"]["column_name"];


        $u = User::first()->eloquentParams;
        $pathTable = $u[$column_name][1];
        $table = ($pathTable)::first()->getTable();
        $dataSource = DB::table($table)->select('id', 'name', 'description')->get();
        $selected = $this->id;
        $colName = $this->colName;
        $currentUser = is_null(User::find($this->id)) ? "" : User::find($this->id)->getAttributes();
        return view('components.controls.dropdown')->with(compact('dataSource', 'selected', 'colName', 'currentUser'));
    }
}
