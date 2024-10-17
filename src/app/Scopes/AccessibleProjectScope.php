<?php

namespace App\Scopes;

use App\Utils\Support\CurrentUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AccessibleProjectScope implements Scope
{
    private $THIRD_PARTY_DEPT_ID = 36;

    public function apply(Builder $builder, Model $model)
    {
        $user = CurrentUser::get();

        if ($user) {
            // if ($user->department == $this->THIRD_PARTY_DEPT_ID) {
            // Apply the scope to only return accessible projects
            $builder->whereHas('getAccessibleUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            // }

            // $builder->whereIn('status', config("project.active_statuses.projects"));
        }
    }
}
