<?php

namespace App\GraphQL\Mutations;

final class UploadFileQaqc
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $file = $args['file'];
    }
}
