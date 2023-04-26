<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityListenDataSource;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitSupportEntityCRUDCreateEdit2;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Json\DefaultValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    use TraitSupportEntityCRUDCreateEdit2;
    use TraitEntityListenDataSource;

    private $type = 'users';
    private $data = Me::class;

    public function getType()
    {
        return $this->type;
    }
    public function profile(Request $request)
    {
        $id = auth()->user()->id;
        $status = $request->query('status');
        $dryRunTokenRequest = $request->query('dryrun_token');
        $valueCreateDryToken = $this->hashDryRunToken($id, $status);
        $this->checkDryRunToken($dryRunTokenRequest, $valueCreateDryToken);
        // dump(SuperProps::getFor($this->type));
        $superProps = $this->getSuperProps();
        $props = $superProps['props'];
        $original = $this->data::findOrFail($id);
        $values = (object) $this->loadValueOfOracyPropsAndAttachments($original, $props);
        $tableBluePrint = $this->makeTableBluePrint($props);
        $tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
        $isCheckColumnStatus = Schema::hasColumn(Str::plural($this->type), 'status');
        return view('dashboards.pages.entity-create-edit', [
            'superProps' => $superProps,
            'props' => $props,
            'item' => $original,
            'defaultValues' => DefaultValues::getAllOf($this->type),
            // 'realtimes' => Realtimes::getAllOf($this->type),
            'values' => $values,
            'status' => $status,
            'dryRunToken' => Hash::make($valueCreateDryToken),
            'isCheckColumnStatus' => $isCheckColumnStatus,
            'type' => Str::plural($this->type),
            'action' => 'edit',
            'modelPath' => $this->data,
            'title' => "Profile",
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'listenerDataSource' => $this->renderListenDataSource($tableToLoadDataSource),
            'listeners2' => $this->getListeners2($this->type),
            'filters2' => $this->getFilters2($this->type),
            'listeners4' => $this->getListeners4($tableBluePrint),
            'filters4' => $this->getFilters4($tableBluePrint),
        ]);
    }
}
