<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Http\Services\DiginetService;
use Illuminate\Http\Request;


abstract class ParentTransferDiginetDataForApi  extends Controller
{
    public function __construct(
        private $endpointNameDiginet = "",
        private $conFieldName = "",
        private $modelName = "",
        private $indexData = 1
    ) {
    }

    protected function initializeData($endpointNameDiginet, $conFieldName, $modelName, $indexData)
    {
        return [$endpointNameDiginet, $conFieldName, $modelName, $indexData];
    }

    public function store(Request $request)
    {
        $params = $request->input();
        dump($params);
        $insService = new DiginetService();
        $result = $insService->createAndUpdateData($this->modelName, $params, $this->endpointNameDiginet, $this->conFieldName, $this->indexData);
        return response()->json($result);
    }
}
