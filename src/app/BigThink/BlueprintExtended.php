<?php

namespace App\BigThink;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class BlueprintExtended extends Blueprint
{
    function hasDueDate()
    {
        $this->dateTime('due_date')->nullable();
    }

    function orderable()
    {
        $this->unsignedInteger('order_no')->nullable();
    }

    function hasStatus()
    {
        $this->string('status')->nullable();
    }

    function closable()
    {
        $this->timestamp('closed_at')->nullable();
    }

    function appendCommonFields()
    {
        $this->unsignedBigInteger('owner_id');
        $this->timestamp('created_at')->useCurrent();
        $this->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        // $this->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        // $this->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        // $this->timestamp('closed_at')->nullable();

        $this->unsignedBigInteger('deleted_by')->nullable();
        $this->softDeletes();
    }
}
