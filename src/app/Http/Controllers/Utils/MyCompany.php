<?php


namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Utils\ClassList;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Http\Request;

class MyCompany extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $rootUid = $request->input('root_uid');
        $viewportUid = $request->input('viewport_uid');
        $leafUid = $request->input('leaf_uid');
        $onlyDirectChildren = $request->input('only_direct_children');
        $flatten = $request->input('flatten');
        $uid = CurrentUser::get()->id;
        $tree = BuildTree::getTreeByOptions($rootUid, $viewportUid, $leafUid, $onlyDirectChildren, $flatten);
        return view(
            "utils/my-company",
            [
                'classListText' => ClassList::TEXT,
                'classListCheckbox' => ClassList::RADIO_CHECKBOX,
                'tree' => $tree,
                'uid' => $uid,
            ]
        );
    }
}
