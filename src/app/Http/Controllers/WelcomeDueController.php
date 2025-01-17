<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ComponentDemo\TraitDemoSpanTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function getTableSpanColumns()
    {
        return  [
            [
                'title' => 'Category',
                'dataIndex' => 'category',
                'width' => 150,
                'headerFilter' => 'input',
                'colspan' => 2
            ],
            [
                'title' => 'Scope',
                'dataIndex' => 'scope',
                'width' => 150,
                'headerFilter' => 'input',
                'colspan' => 2
            ],
            [
                'title' => 'Emission Source Category',
                'dataIndex' => 'emission_source_category',
                'width' => 150,
                'headerFilter' => 'input',
            ],
            [
                'title' => 'Source',
                'dataIndex' => 'source',
                'width' => 150,
                'headerFilter' => 'input',
            ],
            [
                'title' => 'YTD (tCO2e)',
                'dataIndex' => 'ytd_tCO2e',
                'width' => 150,
                'headerFilter' => 'input',
            ],
            [
                'title' => 'Jan 2025',
                'dataIndex' => 'jan_2025',
                'width' => 150,
                'headerFilter' => 'input',
            ],
            [
                'title' => 'Feb 2025',
                'dataIndex' => 'feb_2025',
                'width' => 150,
                'headerFilter' => 'input',
            ],

        ];
    }

    public function getTableSpanDataSource()
    {
        return  [
            [
                'category' => (object)[
                    'rowspan' => 18,
                    'value' => 'GHG Protocol Standards: Corporate Scope - 1 and 2, Value Chain - Scope 3',
                ],
                'scope' => (object)[
                    'rowspan' => 4,
                    'value' => 'Scope 1',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 2,
                    'value' => '1.1 Direct emissions arising from owned or controlled stationary sources that use fossil fuels and/or emit fugitive emissions',
                ],
                'source' => "Gaseous Fuel",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => "XYZ",
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '',
                ],
                'source' => "Refrigerants",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => "XYZ",
                'emission_source_category' => (object)[
                    'rowspan' => 2,
                    'value' => '1.2 Direct emissions from owned or controlled mobile sources',
                ],
                'source' => "Own Passenger Vehicles",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,

            ],
            [
                'category' => "ABC",
                'scope' => "XYZ",
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '1.2 Direct emissions from owned or controlled mobile sources',
                ],
                'source' => "Delivery Controlled Vehicles",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,

            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 2',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '2.1 Location-based emissions from the generation of purchased electricity and transmission & distrubution loss',
                ],
                'source' => "Electricity",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,

            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 12,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 2,
                    'value' => '3.1 Purchased goods',
                ],
                'source' => "Water Supply Treatment",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,

            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '3.1 Purchased goods',
                ],
                'source' => "Materials",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '3.2 Waste generated in operations',
                ],
                'source' => "Waste Disposal",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 3,
                    'value' => '3.3 Business travel',
                ],
                'source' => "Bussiness Travel Land Sea",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '3.3 Business travel',
                ],
                'source' => "Hotel Stay",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '3.3 Business travel',
                ],
                'source' => "Business Travel Air",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],

            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 2,
                    'value' => '3.4 Upstream transportation and distribution',
                ],
                'source' => "Freighting goods Land Sea",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '3.4 Upstream transportation and distribution',
                ],
                'source' => "Freight Goods Air",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],

            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '3.5 Commuting',
                ],
                'source' => "Employee Commuting",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],

            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 2,
                    'value' => '3.6 Manage Assets',
                ],
                'source' => "Manage Asset Electricity",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '3.6 Manage Assets',
                ],
                'source' => "Manage Asset Vehicle",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],

            [
                'category' => "ABC",
                'scope' => (object)[
                    'rowspan' => 1,
                    'value' => 'Scope 3',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '3.7 Home office',
                ],
                'source' => "Work From Home",
                'ytd_tCO2e' => 5,
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],
            [
                'category' => "ABC",
                'scope' => (object)[
                    'colspan' => 3,
                    'value' => 'Total Emissions',
                ],
                'emission_source_category' => (object)[
                    'rowspan' => 1,
                    'value' => '',
                ],
                'source' => "Work From Home",
                'ytd_tCO2e' => '1000',
                'jan_2025' => 7,
                'feb_2025' => 7,
            ],


          
        ];
    }

    public function index(Request $request)
    {


        return view("welcome-due", [
            'tableSpanColumns' => $this->getTableSpanColumns(),
            'tableSpanDataSource' => $this->getTableSpanDataSource(),
        ]);
    }
}
