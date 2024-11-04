<?php

namespace App\View\Components\Renderer\OrgChart;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class OrgChartRendererExternal extends Component
{
    function __construct(
        private $id = 0,
    ) {}

    private function spreadUser(Collection $users, int $subProjectId, string $thirdPartyType)
    {
        $result = [];
        if ($users->count() > 0) {
            $key = "sub_project_" . $subProjectId . "_" . $thirdPartyType;
            foreach ($users as $user) {
                $src = $user->getAvatar ? app()->pathMinio() . $user->getAvatar->url_thumbnail : "/images/avatar.jpg";
                $img = "<img src='$src' class='rounded-full ml-2 mr-2' heigh=24 width=24 />";
                $idStr = Str::makeId($user->id);
                $href = route('users.edit', $user->id);
                $idStr = " (<a href='$href' class='text-blue-500'>$idStr</a>)";
                $text = "<span class='flex 1-mt-6'>" . $img . $user->name . " - " . $user->email .  $idStr .  "</span>";
                $item3 = [
                    'id' => $key . "_user_" . $user->id,
                    'text' => $text,
                    'name_for_sort' => $user->name,
                    'parent' => $key,
                    'icon' => false,
                ];
                $result[] = $item3;
            }

            usort($result, function ($a, $b) {
                return $a['name_for_sort'] <=> $b['name_for_sort'];
            });
        }
        return $result;
    }

    public function render()
    {
        $projects = Project::query()
            ->with(["getSubProjects" => function ($q) {
                $q->with([
                    "getProjectClientsOfSubProject" => function ($q) {
                        $q->with('getAvatar');
                    },
                    "getExternalInspectorsOfSubProject" => function ($q) {
                        $q->with('getAvatar');
                    },
                    "getCouncilMembersOfSubProject" => function ($q) {
                        $q->with('getAvatar');
                    },
                    "getShippingAgentsOfSubProject" => function ($q) {
                        $q->with('getAvatar');
                    },
                ])
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $projectTree = [];
        $subProjectTree = [];
        $thirdPartyTree = [];
        $userTree = [];

        foreach ($projects as $project) {
            $subProjects = $project->getSubProjects;
            $item1 = [
                'id' => "project_" . $project->id,
                'text' => $project->name,
                'parent' => '#',
                'state' => ['opened' => true],
            ];


            foreach ($subProjects as $subProject) {
                $item2 = [
                    'id' => "sub_project_" . $subProject->id,
                    'text' => $subProject->name,
                    'parent' => 'project_' . $project->id,
                    'state' => ['opened' => true],
                ];


                $clients = $subProject->getProjectClientsOfSubProject;
                $externalInspectors = $subProject->getExternalInspectorsOfSubProject;
                $councilMembers = $subProject->getCouncilMembersOfSubProject;
                $shippingAgents = $subProject->getShippingAgentsOfSubProject;

                $users = [
                    "Project Clients" => $this->spreadUser($clients, $subProject->id, 'Project Clients'),
                    "External Inspectors" => $this->spreadUser($externalInspectors, $subProject->id, 'External Inspectors'),
                    "Council Members" => $this->spreadUser($councilMembers, $subProject->id, 'Council Members'),
                    "Shipping Agents" => $this->spreadUser($shippingAgents, $subProject->id, 'Shipping Agents'),
                ];

                $count = 0;
                foreach ($users as $user) $count += count($user);

                if ($count > 0) {
                    $projectTree[$project->id] = $item1;
                    $subProjectTree[$subProject->id] = $item2;

                    foreach ($users as $thirdPartyType  => $userList) {
                        if (count($userList) > 0) {
                            $key = "sub_project_" . $subProject->id . "_" . $thirdPartyType;
                            $item3 = [
                                'id' => $key,
                                'text' => $thirdPartyType,
                                'parent' => 'sub_project_' . $subProject->id,
                                'state' => ['opened' => true],
                            ];
                            $thirdPartyTree[$key] = $item3;

                            $userTree = array_merge($userTree, $userList);
                        }
                    }
                }
            }
        }

        // dump($userTree);

        $jsonTree = [
            ...array_values($projectTree),
            ...array_values($subProjectTree),
            ...array_values($thirdPartyTree),
            ...array_values($userTree),
        ];

        // dump($jsonTree);
        // $users = User::query()
        //     ->where('department', 36)
        //     ->where('resigned', 0)
        //     ->where('show_on_beta', 0)
        //     ->with([
        //         "getUserDiscipline",
        //         "getUserCompany",
        //         "getAvatar",
        //         "getSubProjectsOfCouncilMember",
        //         "getSubProjectsOfProjectClient",
        //         "getSubProjectsOfExternalInspector",
        //         "getSubProjectsOfShippingAgent",
        //     ])
        //     ->get();


        // foreach ($users as $user) {
        //     $company = $user->getUserCompany;
        //     $companyId = $company?->id;
        //     $key1 = 'company_' . $companyId;
        //     if (!isset($jsonTree[$key1])) {
        //         $jsonTree[$key1] = [
        //             'id' => $key1,
        //             'text' => $company?->name ?? '(Orphan Company)',
        //             'parent' => '#',
        //             'state' => ['opened' => true],
        //         ];
        //     }

        //     $discipline = $user->getUserDiscipline;
        //     $disciplineId = $discipline?->id;
        //     $key2 = $key1 . '_discipline_' . $disciplineId;
        //     if (!isset($jsonTree[$key2])) {
        //         $jsonTree[$key2] = [
        //             'id' => $key2,
        //             'text' => $discipline?->name,
        //             'parent' => $key1,
        //             'state' => ['opened' => true],
        //         ];
        //     }

        //     $src = $user->getAvatar ? app()->pathMinio() . $user->getAvatar->url_thumbnail : "/images/avatar.jpg";
        //     $img = "<img src='$src' class='rounded-full ml-12 mr-2' heigh=24 width=24 />";

        //     $userId = $user->id;
        //     $key3 = $key2 . '_user_' . $userId;
        //     if (!isset($jsonTree[$key3])) {
        //         $idStr = Str::makeId($userId);
        //         $href = route('users.edit', $userId);
        //         $idStr = " (<a href='$href' class='text-blue-500'>$idStr</a>)";
        //         $jsonTree[$key3] = [
        //             'id' => $key3,
        //             'text' => "<span class='flex -mt-6'>" . $img . $user->name . " - " . $user->email .  $idStr .  "</span>",
        //             'parent' => $key2,
        //             // 'state' => ['opened' => true],
        //         ];
        //     }
        // }

        // $value = array_values($jsonTree);
        // usort($value, function ($a, $b) {
        //     return $a['text'] <=> $b['text'];
        // });

        return view("components.renderer.org-chart.org-chart-renderer-external", [
            'id' => $this->id,
            'jsonTree' => $jsonTree,
        ]);
    }
}
