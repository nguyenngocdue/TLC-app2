<?php

namespace App\BigThink;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class BlueprintExtended extends Blueprint
{
    function orderable()
    {
        $this->unsignedInteger('order_no')->nullable();
    }

    function closable()
    {
        $this->timestamp('closed_at')->nullable();
    }

    function appendCommonFields()
    {
        $this->string('status')->nullable();
        $this->unsignedBigInteger('owner_id');

        $this->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        $this->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        // $this->timestamp('closed_at')->nullable();
        // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

        $this->unsignedBigInteger('deleted_by')->nullable();
        $this->softDeletes();
    }
}
