<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitManageJson\TraitManageActionButtons;
use App\Http\Controllers\Entities\ZZTraitManageJson\TraitManageListeners;
use App\Http\Controllers\Entities\ZZTraitManageJson\TraitManageProps;
use App\Http\Controllers\Entities\ZZTraitManageJson\TraitManageRelationships;
use App\Http\Controllers\Entities\ZZTraitManageJson\TraitManageStatuses;
use App\Http\Controllers\Entities\ZZTraitManageJson\TraitManageTransitions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Pages
{
    const Prop = "Prop";
    const Listener = "Listener";
    const Relationship = "Relationship";
    const Status = "Status";
    const Transition = "Transition";
    const ActionButton = "ActionButton";
}

abstract class AbstractManageJsonController extends Controller
{
    use TraitManageListeners;
    use TraitManageProps;
    use TraitManageStatuses;
    use TraitManageRelationships;
    use TraitManageTransitions;
    use TraitManageActionButtons;

    protected $type = "";
    protected $typeModel = "";

    private $list = [
        "_prp" => Pages::Prop,
        "_ltn" => Pages::Listener,
        "_stt" => Pages::Status,
        "_rls" => Pages::Relationship,
        "_tst" => Pages::Transition,
        "_atb" => Pages::ActionButton,
    ];

    public function getType()
    {
        return $this->type;
    }

    protected function getPage(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        foreach ($this->list as $key => $value) if (strpos($pathInfo, $key)) return $value;
        return "Unknown pathInfo $pathInfo";
    }

    protected function getTitle(Request $request)
    {
        return "Manage " . Str::plural($this->getPage($request));
    }

    public function index(Request $request)
    {
        $page = $this->getPage($request);
        if (in_array($page, $this->list))
            return $this->{"index{$page}"}($request);
        dd($page);
    }

    public function create(Request $request)
    {
        $page = $this->getPage($request);
        if (in_array($page, $this->list))
            return $this->{"create{$page}"}($request);
        dd($page);
    }

    public function store(Request $request)
    {
        $page = $this->getPage($request);
        if (in_array($page, $this->list))
            return $this->{"store{$page}"}($request);
        dd($page);
    }
}
