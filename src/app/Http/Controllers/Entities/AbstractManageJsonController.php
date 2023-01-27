<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitManageJson\Manage_Parent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class Pages
{
    const Property = "Property";
    const Prop = "Prop";
    const DefaultValue = "DefaultValue";
    const Listener = "Listener";
    const Relationship = "Relationship";
    const Status = "Status";
    const Transition = "Transition";
    const ActionButton = "ActionButton";
    const Definition = "Definition";
    const IntermediateProp = "IntermediateProp";
    const BallInCourt = "BallInCourt";

    const VisibleProp = "VVisibleProp";
    const ReadOnlyProp = "VReadOnlyProp";
    const RequiredProp = "VRequiredProp";
    const HiddenProp = "VHiddenProp";

    const VisibleWLProp = "VVisibleWLProp";
    const ReadOnlyWLProp = "VReadOnlyWLProp";
    const RequiredWLProp = "VRequiredWLProp";
    const HiddenWLProp = "VHiddenWLProp";

    const Capability = "Capability";
    const UnitTest = "UnitTest";
}

abstract class AbstractManageJsonController extends Controller
{
    protected $type = "";
    protected $typeModel = "";

    private $pages = [
        "_ppt" => Pages::Property,
        "_prp" => Pages::Prop,
        "_dfv" => Pages::DefaultValue,
        "_ltn" => Pages::Listener,
        "_stt" => Pages::Status,
        "_rls" => Pages::Relationship,
        "_tst" => Pages::Transition,
        "_atb" => Pages::ActionButton,
        "_dfn" => Pages::Definition,
        "_itm" => Pages::IntermediateProp,
        "_bic" => Pages::BallInCourt,

        "_vsb" => Pages::VisibleProp,
        "_rol" => Pages::ReadOnlyProp,
        "_rqr" => Pages::RequiredProp,
        "_hdn" => Pages::HiddenProp,

        "_vsb-wl" => Pages::VisibleWLProp,
        "_rol-wl" => Pages::ReadOnlyWLProp,
        "_rqr-wl" => Pages::RequiredWLProp,
        "_hdn-wl" => Pages::HiddenWLProp,

        "_cpb" => Pages::Capability,
        "_unt" => Pages::UnitTest,
    ];

    private function getPathInfoWithoutCreate(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        // /config/attachment_abc/create => attachment_abc
        $posOfCreate = strrpos($pathInfo, "/create");
        if ($posOfCreate !== false) $pathInfo = substr($pathInfo, 0, $posOfCreate);
        // /config/attachment_abc => attachment_abc
        $path = substr($pathInfo, strrpos($pathInfo, "/") + 1);
        return $path;
    }

    public function getType(Request $request = null)
    {
        if (is_null($request)) return $this->type;
        $path = $this->getPathInfoWithoutCreate($request);
        $path = substr($path, 0, strrpos($path, "_")); // attachment_abc => attachment
        return $path;
    }

    protected function getPage(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $path = $this->getPathInfoWithoutCreate($request);
        $path = substr($path, strrpos($path, "_"));
        if (isset($this->pages[$path])) return $this->pages[$path];
        return "Unknown pathInfo $pathInfo, key $path";
    }

    protected function getTitle(Request $request)
    {
        return "Manage " . Str::plural($this->getPage($request));
    }

    private function getInstance(Request $request)
    {
        $page = $this->getPage($request);
        if (!in_array($page, $this->pages)) dd($page);
        $className = __NAMESPACE__ . "\\ZZTraitManageJson\\Manage" . Str::plural($page);
        $type = $this->getType($request);
        $typeModel = "App\\Models\\$type";
        /** @var Manage_Parent $instance */
        $instance = new $className($type, $typeModel);
        // dump($page, $className, $type, $typeModel);
        return $instance;
    }

    public function index(Request $request)
    {
        return $this->getInstance($request)->index($request);
    }

    public function create(Request $request)
    {
        return $this->getInstance($request)->create($request);
    }

    public function store(Request $request)
    {
        return $this->getInstance($request)->store($request);
    }

    function attachActionButtons(&$dataSource, $key, array $buttons)
    {
        $results = [];
        foreach ($buttons as $button) {
            switch ($button) {
                case "up":
                    $results[] = "<x-renderer.button htmlType='submit' name='button' size='xs' value='up,$key'><i class='fa fa-arrow-up'></i></x-renderer.button>";
                    break;
                case "down":
                    $results[] = "<x-renderer.button htmlType='submit' name='button' size='xs' value='down,$key'><i class='fa fa-arrow-down'></i></x-renderer.button>";
                    break;
                case "right_by_name":
                    $results[] = "<x-renderer.button htmlType='submit' name='button' size='xs' value='right_by_name,$key' type='danger' outline=true><i class='fa fa-trash'></i></x-renderer.button>";
                    break;
            }
        }
        $result = join(" ", $results);
        $dataSource[$key]['action'] = Blade::render("<div class='whitespace-nowrap'>$result</div>");
    }
}
