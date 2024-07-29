<?php

namespace App\View\Components\Homepage;

use Illuminate\View\Component;

class TopDrawer2 extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        $tabPans = [
            [
                'id' => 'applications',
                'title' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i> Applications',
                'jsOnMouseOver' => "
                    $('#topDrawer2Applications').show(); 
                    $('#topDrawer2Reports').hide(); 
                    $('#topDrawer2Documents').hide(); 

                    toggleTabPan('applications');
                    ",

                'active' => 1,
            ],
            [
                'id' => 'reports',
                'title' => '<i class="text-green-600 fa-duotone fa-file-chart-column"></i> Reports',
                'jsOnMouseOver' => "
                    $('#topDrawer2Applications').hide(); 
                    $('#topDrawer2Reports').show(); 
                    $('#topDrawer2Documents').hide(); 

                    toggleTabPan('reports');
                    ",
            ],
            [
                'id' => 'documents',
                'title' => '<i class="text-orange-600 fa-duotone fa-file-lines"></i> Documents',
                'jsOnMouseOver' => "
                    $('#topDrawer2Applications').hide(); 
                    $('#topDrawer2Reports').hide(); 
                    $('#topDrawer2Documents').show(); 

                    toggleTabPan('documents');
                    ",
            ],
        ];
        $dataSource = [

            [
                'id' => "Project Managements",
                'title' => "Project Managements",
                'count' => 123,
                'items' => [
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                    [
                        'id' => 1,
                        'title' => "Project 1",
                        'count' => 123,
                        'href' => '#',
                        'icon' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i>',
                        'create_new_href' => '#',
                        'bookmark_href' => '#',
                    ],
                ],
            ],
            [
                'id' => "Quality Controls",
                'title' => "Quality Controls",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "Productions",
                'title' => "Productions",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "Task Managements",
                'title' => "Task Managements",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "HR Admins",
                'title' => "HR Admins",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "Exams",
                'title' => "Exams",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "Quality Assurances",
                'title' => "Quality Assurances",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "Compliance Datas",
                'title' => "Compliance Datas",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "ESG",
                'title' => "ESG",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "HSE",
                'title' => "HSE",
                'count' => 123,
                'items' => [],
            ],
            [
                'id' => "Accounting",
                'title' => "Accounting",
                'count' => 123,
                'items' => [],
            ],

        ];
        return view('components.homepage.top-drawer2', [
            'tabPans' => $tabPans,
            'dataSource' => $dataSource,
            'route' => route('updateBookmark'),
        ]);
    }
}
