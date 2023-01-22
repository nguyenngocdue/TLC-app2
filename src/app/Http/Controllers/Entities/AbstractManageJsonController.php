<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitManageJson\Manage_Parent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class Pages
{
    const Prop = "Prop";
    const DefaultValue = "DefaultValue";
    const Listener = "Listener";
    const Relationship = "Relationship";
    const Status = "Status";
    const Transition = "Transition";
    const ActionButton = "ActionButton";
    const Setting = "Setting";
    const BallInCourt = "BallInCourt";
    const UnitTest = "UnitTest";

    const VisibleProp = "VVisibleProp";
    const ReadOnlyProp = "VReadOnlyProp";
    const RequiredProp = "VRequiredProp";
    const HiddenProp = "VHiddenProp";

    const ReadOnlyWLProp = "VReadOnlyWLProp";
    const HiddenWLProp = "VHiddenWLProp";
    const Capability = "Capability";
}

abstract class AbstractManageJsonController extends Controller
{
    protected $type = "";
    protected $typeModel = "";

    private $pages = [
        "_prp" => Pages::Prop,
        "_dfv" => Pages::DefaultValue,
        "_ltn" => Pages::Listener,
        "_stt" => Pages::Status,
        "_rls" => Pages::Relationship,
        "_tst" => Pages::Transition,
        "_atb" => Pages::ActionButton,
        "_stn" => Pages::Setting,
        "_bic" => Pages::BallInCourt,
        "_unt" => Pages::UnitTest,

        "_vsb" => Pages::VisibleProp,
        "_rol" => Pages::ReadOnlyProp,
        "_rqr" => Pages::RequiredProp,
        "_hdn" => Pages::HiddenProp,

        "_rol-wl" => Pages::ReadOnlyWLProp,
        "_hdn-wl" => Pages::HiddenWLProp,

        "_cpb" => Pages::Capability,
    ];

    public function getType(Request $request = null)
    {
        if (is_null($request)) return $this->type;
        $pathInfo = $request->getPathInfo();
        $path = substr($pathInfo, strrpos($pathInfo, "/") + 1); // /config/attachment_abc => attachment_abc
        $path = substr($path, 0, strrpos($path, "_")); // attachment_abc => attachment
        return $path;
    }

    protected function getPage(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        // foreach ($this->pages as $key => $value) if (strpos($pathInfo, $key)) return $value;
        $path = substr($pathInfo, strrpos($pathInfo, "/") + 1); // /config/attachment_abc => attachment_abc
        $path = substr($path, strrpos($path, "_")); // attachment_abc-def => _abc-def
        if (isset($this->pages[$path])) return $this->pages[$path];
        return "Unknown pathInfo $pathInfo";
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
