<?php

namespace App\Http\Services\GetLineForModal;

use App\Models\User_company;
use Illuminate\Support\Facades\Log;

class UserGetLineService extends _GetLineService
{
    public function getLines()
    {
        $allCompanies = User_company::query()
            ->with(['getUsers' => function ($query) {
                $query
                    ->with(['getAvatar', 'getUserDepartment'])
                    ->where('resigned', '0')
                    ->where('show_on_beta', '0')
                    ->orderBy('first_name')
                ;
            }])
            ->get();

        $tree = [];
        foreach ($allCompanies as $company) {
            $comItem = [
                "id" => "company_" . $company->id,
                "parent" => "#",
                "text" => $company->name,
                'data' => ['type' => 'company'],
            ];
            $departments = [];
            foreach ($company->getUsers as $user) {
                if ($department = $user->getUserDepartment) {
                    $departments[$company->id . "_" . $department->id] = [
                        'id' => $company->id . '_department_' . $department->id,
                        'text' => $department->name,
                        'parent' => 'company_' . $company->id,
                        'data' => ['type' => 'department'],
                    ];
                }
                $src = $user->getAvatar ? app()->pathMinio() . $user->getAvatar->url_thumbnail : "/images/avatar.jpg";
                $img = "<img src='$src' class='rounded-full ml-12 mr-2' heigh=24 width=24 />";
                $item = [
                    "id" => "user_" . $user->id,
                    "parent" => $user->company . "_department_" . $user->department,
                    "text" => "<span class='flex -mt-6'>" . $img . $user->name . "</span>",
                    'data' => ['type' => 'user', 'user_id' => $user->id],
                    "icon" => '',
                ];
                $tree[] = $item;
            }
            uasort($departments, fn($a, $b) => $a['text'] <=> $b['text']);
            foreach ($departments as $department) $tree[] = $department;
            $tree[] = $comItem;
        }
        return $tree;
    }
}
