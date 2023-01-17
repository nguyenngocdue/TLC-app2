<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Pages
{
    const Prop = "Prop";
    const Listener = "Listener";
    const Relationship = "Relationship";
    const Status = "Status";
}

abstract class AbstractManageJsonController extends Controller
{
    use TraitManageListeners;
    use TraitManageProps;
    use TraitManageStatuses;
    use TraitManageRelationships;

    protected $type = "";
    protected $typeModel = "";

    public function getType()
    {
        return $this->type;
    }

    protected function getPage(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        if (strpos($pathInfo, "_prp")) return Pages::Prop;
        if (strpos($pathInfo, "_ltn")) return Pages::Listener;
        if (strpos($pathInfo, "_stt")) return Pages::Status;
        if (strpos($pathInfo, "_rls")) return Pages::Relationship;
        return "Unknown pathInfo $pathInfo";
    }

    protected function getTitle(Request $request)
    {
        return "Manage " . Str::plural($this->getPage($request));
    }

    public function index(Request $request)
    {
        $page = $this->getPage($request);
        switch ($page) {
            case Pages::Prop:
                return $this->indexProp($request);
            case Pages::Relationship:
                return $this->indexRelationship($request);
            case Pages::Listener:
                return $this->indexListener($request);
            case Pages::Status:
                return $this->indexStatus($request);
            default:
                dd($page);
        }
    }

    public function create(Request $request)
    {
        $page = $this->getPage($request);
        switch ($page) {
            case Pages::Prop:
                return $this->createProp($request);
            case Pages::Relationship:
                return $this->createRelationship($request);
            case Pages::Listener:
                return $this->createListener($request);
            case Pages::Status:
                return $this->createStatus($request);
            default:
                dd($page);
        }
    }

    public function store(Request $request)
    {
        $page = $this->getPage($request);
        switch ($page) {
            case Pages::Prop:
                return $this->storeProp($request);
            case Pages::Relationship:
                return $this->storeRelationship($request);
            case Pages::Listener:
                return $this->storeListener($request);
            case Pages::Status:
                return $this->storeStatus($request);
            default:
                dd($page);
        }
    }
}
