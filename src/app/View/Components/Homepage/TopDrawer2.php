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
                    $('#tab-pan-applications').addClass('bg-white -mb-px').removeClass('bg-gray-200');

                    $('#topDrawer2Reports').hide(); 
                    $('#tab-pan-reports').removeClass('bg-white -mb-px').addClass('bg-gray-200');

                    $('#topDrawer2Documents').hide(); 
                    $('#tab-pan-documents').removeClass('bg-white -mb-px').addClass('bg-gray-200');",
                'active' => 1,
            ],
            [
                'id' => 'reports',
                'title' => '<i class="text-green-600 fa-duotone fa-file-chart-column"></i> Reports',
                'jsOnMouseOver' => "
                    $('#topDrawer2Applications').hide(); 
                    $('#tab-pan-applications').removeClass('bg-white -mb-px').addClass('bg-gray-200');

                    $('#topDrawer2Reports').show(); 
                    $('#tab-pan-reports').addClass('bg-white -mb-px').removeClass('bg-gray-200');

                    $('#topDrawer2Documents').hide(); 
                    $('#tab-pan-documents').removeClass('bg-white -mb-px').addClass('bg-gray-200');
                    ",
            ],
            [
                'id' => 'documents',
                'title' => '<i class="text-orange-600 fa-duotone fa-file-lines"></i> Documents',
                'jsOnMouseOver' => "
                    $('#topDrawer2Applications').hide(); 
                    $('#tab-pan-applications').removeClass('bg-white -mb-px').addClass('bg-gray-200');

                    $('#topDrawer2Reports').hide(); 
                    $('#tab-pan-reports').removeClass('bg-white -mb-px').addClass('bg-gray-200');

                    $('#topDrawer2Documents').show(); 
                    $('#tab-pan-documents').addClass('bg-white -mb-px').removeClass('bg-gray-200');
                    ",
            ],
        ];
        return view('components.homepage.top-drawer2', [
            'tabPans' => $tabPans,
            'route' => route('updateBookmark'),
        ]);
    }
}
