<?php

namespace App\View\Components\Controls;

use App\Http\Controllers\Workflow\LibAppCreations;
use App\Http\Controllers\Workflow\LibApps;
use Illuminate\View\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DisallowedDirectCreationChecker extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
    ) {
        //
    }

    public static function getCreationLinks($type)
    {
        $app = LibAppCreations::getFor($type);
        // dump($type);
        // dump($app);
        return $app['creation_links'] ?? " its appropriate parent";
    }

    public static function check(Request $request, $type)
    {
        $app = LibApps::getFor($type);
        $disallowed_direct_creation = isset($app['disallowed_direct_creation']) &&  $app['disallowed_direct_creation'] == true;
        if ($disallowed_direct_creation) {
            $appCreation = LibAppCreations::getFor($type);
            $requiredParams = explode(",", $appCreation['required_params']);
            $requiredParams = array_map(fn ($item) => trim($item), $requiredParams);

            // dump($appCreation);
            // dump($requiredParams);

            foreach ($requiredParams as $param) {
                if (!$request->has($param)) {
                    Log::info("Missing $param parameter during creation of $type");
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $msg = "You can only create this document via this link";
        // $title = "Error";
        // return "<x-feedback.alert message='$msg' type='error' title='$title' />";
    }
}
