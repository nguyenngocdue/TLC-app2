<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;

trait TraitCloneRequest
{
    function cloneRequest(Request $request)
    {
        $newRequest = Request::create(
            $request->path(),
            $request->method(),
            $request->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $request->getContent()
        );

        // You can also modify properties of the new request, if needed
        // $newRequest->headers->set('Custom-Header', 'Custom Value');
        return $newRequest;
    }
}
