<?php

namespace App\View\Components\Renderer\OrgChart;

use App\Models\User;
use Illuminate\View\Component;

class OrgChartRendererExternal extends Component
{
    function __construct(
        private $id = 0,
    ) {}

    public function render()
    {
        $users = User::query()
            ->where('department', 36)
            ->where('resigned', 0)
            ->where('show_on_beta', 0)
            ->with(["getUserDiscipline", "getUserCompany", "getAvatar"])
            ->get();

        $jsonTree = [];

        foreach ($users as $user) {
            $company = $user->getUserCompany;
            $companyId = $company?->id;
            $key1 = 'company_' . $companyId;
            if (!isset($jsonTree[$key1])) {
                $jsonTree[$key1] = [
                    'id' => $key1,
                    'text' => $company?->name ?? '(Orphan Company)',
                    'parent' => '#',
                    'state' => ['opened' => true],
                ];
            }

            $discipline = $user->getUserDiscipline;
            $disciplineId = $discipline?->id;
            $key2 = $key1 . '_discipline_' . $disciplineId;
            if (!isset($jsonTree[$key2])) {
                $jsonTree[$key2] = [
                    'id' => $key2,
                    'text' => $discipline?->name,
                    'parent' => $key1,
                    'state' => ['opened' => true],
                ];
            }

            $src = $user->getAvatar ? app()->pathMinio() . $user->getAvatar->url_thumbnail : "/images/avatar.jpg";
            $img = "<img src='$src' class='rounded-full ml-12 mr-2' heigh=24 width=24 />";

            $userId = $user->id;
            $key3 = $key2 . '_user_' . $userId;
            if (!isset($jsonTree[$key3])) {
                $jsonTree[$key3] = [
                    'id' => $key3,
                    'text' => "<span class='flex -mt-6'>" . $img . $user->name . "</span>",
                    'parent' => $key2,
                    // 'state' => ['opened' => true],
                ];
            }
        }

        $value = array_values($jsonTree);
        usort($value, function ($a, $b) {
            return $a['text'] <=> $b['text'];
        });

        return view("components.renderer.org-chart.org-chart-renderer-external", [
            'id' => $this->id,
            'jsonTree' => $value,
        ]);
    }
}
