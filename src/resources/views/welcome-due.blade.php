@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Combobox</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-6 bg-white rounded shadow-lg">
        <button id="addComboboxBtn" class="mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Plus
        </button>
        <div id="comboboxContainer" class="space-y-4"></div>
    </div>

    <script>
        document.getElementById('addComboboxBtn').addEventListener('click', function() {
            // Create a new div to hold the combobox
            const newDiv = document.createElement('div');
            newDiv.className = 'combobox';

            // Create a new combobox (select element)
            const newSelect = document.createElement('select');
            newSelect.className = 'combobox-select block w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500';

            // Add options to the combobox
            const option1 = document.createElement('option');
            option1.value = 'option1';
            option1.text = 'Option 1';
            newSelect.appendChild(option1);

            const option2 = document.createElement('option');
            option2.value = 'option2';
            option2.text = 'Option 2';
            newSelect.appendChild(option2);

            // Append the combobox to the div
            newDiv.appendChild(newSelect);

            // Append the div to the container
            document.getElementById('comboboxContainer').appendChild(newDiv);
        });
    </script>
</body>
</html>

@endsection


{{-- 
Fields

INSERT INTO `fields` (`id`, `name`, `reversed_name`, `description`, `owner_id`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`) VALUES
 (300, 'getBlackWhite', NULL, NULL, '1', '2024-07-02 08:01:13', '2024-07-02 08:01:13', NULL, NULL),
 (301, 'getIconPosition', NULL, NULL, '1', '2024-07-04 08:01:13', '2024-07-04 08:01:13', NULL, NULL),
 (302, 'getTableToolboxType', NULL, NULL, '1', '2024-07-03 08:01:13', '2024-07-03 08:01:13', NULL, NULL),
 (303, 'getFilterControlType', NULL, NULL, '1', '2024-07-03 08:01:13', '2024-07-03 08:01:13', NULL, NULL),
 (304, 'getRendererType', NULL, NULL, '1', '2024-07-06 08:01:13', '2024-07-06 08:01:13', NULL, NULL), 
 (305, 'getFooterAgg', NULL, NULL, '1', '2024-07-08 08:01:13', '2024-07-08 08:01:13', NULL, NULL),
 (306, 'getRowRenderer', NULL, NULL, '1', '2024-07-09 08:01:13', '2024-07-09 08:01:13', NULL, NULL),
 (308, 'getChartType', NULL, NULL, '1', '2024-07-10 08:01:13', '2024-07-10 08:01:13', NULL, NULL)


Terms

INSERT INTO `terms` (`id`, `name`, `description`, `slug`, `field_id`, `parent1_id`, `parent2_id`, `parent3_id`, `parent4_id`, `owner_id`, `created_at`, `updated_at`, `deleted_by`, `deleted_at`) VALUES 
 (601, 'Black', 'black_or_white', 'black-or-white', '300', NULL, NULL, NULL, NULL, 1, '2024-07-02 08:01:13', '2024-07-02 08:01:13', NULL, NULL),
 (602, 'White', 'black_or_white', 'black-or-white-1', '300', NULL, NULL, NULL, NULL, 1, '2024-07-03 08:01:13', '2024-07-03 08:01:13', NULL, NULL),
 
 (611, 'Left', 'Icon-position', 'icon-position', '301', NULL, NULL, NULL, NULL, 1, '2024-07-06 08:01:13', '2024-07-06 08:01:13', NULL, NULL),
 (612, 'Right', 'Icon-position', 'icon-position-1', '301', NULL, NULL, NULL, NULL, 1, '2024-07-07 08:01:13', '2024-07-07 08:01:13', NULL, NULL),
 (613, 'Top', 'Icon-position', 'icon-position-2', '301', NULL, NULL, NULL, NULL, 1, '2024-07-08 08:01:13', '2024-07-08 08:01:13', NULL, NULL),
 (614, 'Bottom', 'Icon-position', 'icon-position-3', '301', NULL, NULL, NULL, NULL, 1, '2024-07-09 08:01:13', '2024-07-09 08:01:13', NULL, NULL),

 (621, 'Export', 'Util ', 'util', '302', NULL, NULL, NULL, NULL, 1, '2024-07-04 08:01:13', '2024-07-04 08:01:13', NULL, NULL),
 (622, 'Pagination', 'Util ', 'util-1', '302', NULL, NULL, NULL, NULL, 1, '2024-07-05 08:01:13', '2024-07-05 08:01:13', NULL, NULL),

 (631, 'Dropdown', 'control_type', 'control-type', '303', NULL, NULL, NULL, NULL, 1, '2024-07-08 08:01:13', '2024-07-08 08:01:13', NULL, NULL),
 (632, 'Dropdown Month', 'control_type', 'control-type-1', '303', NULL, NULL, NULL, NULL, 1, '2024-07-09 08:01:13', '2024-07-09 08:01:13', NULL, NULL),
 (633, 'Text / Number', 'control_type', 'control-type-2', '303', NULL, NULL, NULL, NULL, 1, '2024-07-10 08:01:13', '2024-07-10 08:01:13', NULL, NULL),
 
 (641, 'Table', 'renderer_type', 'renderer-type', '304', NULL, NULL, NULL, NULL, 1, '2024-07-11 08:01:13', '2024-07-11 08:01:13', NULL, NULL),
 (642, 'Chart', 'renderer_type', 'renderer-type-1', '304', NULL, NULL, NULL, NULL, 1, '2024-07-12 08:01:13', '2024-07-12 08:01:13', NULL, NULL),
 (643, 'Paragraph', 'renderer_type', 'renderer-type-2', '304', NULL, NULL, NULL, NULL, 1, '2024-07-13 08:01:13', '2024-07-13 08:01:13', NULL, NULL),
 (644, 'Description', 'renderer_type', 'table-information-renderer-type', '304', NULL, NULL, NULL, NULL, 1, '2024-07-14 08:01:13', '2024-07-14 08:01:13', NULL, NULL),
 
 (651, 'agg_none', 'agg_footer', 'agg-footer', '305', NULL, NULL, NULL, NULL, 1, '2024-07-15 08:01:13', '2024-07-15 08:01:13', NULL, NULL),
 (652, 'agg_count_all', 'agg_footer', 'agg-footer-1', '305', NULL, NULL, NULL, NULL, 1, '2024-07-16 08:01:13', '2024-07-16 08:01:13', NULL, NULL),
 (653, 'agg_sum', 'agg_footer', 'agg-footer-2', '305', NULL, NULL, NULL, NULL, 1, '2024-07-17 08:01:13', '2024-07-17 08:01:13', NULL, NULL),
 (654, 'agg_avg', 'agg_footer', 'agg-footer-3', '305', NULL, NULL, NULL, NULL, 1, '2024-07-18 08:01:13', '2024-07-18 08:01:13', NULL, NULL),
 (655, 'agg_median', 'agg_footer', 'agg-footer-4', '305', NULL, NULL, NULL, NULL, 1, '2024-07-19 08:01:13', '2024-07-19 08:01:13', NULL, NULL),
 (656, 'agg_min', 'agg_footer', 'agg-footer-5', '305', NULL, NULL, NULL, NULL, 1, '2024-07-20 08:01:13', '2024-07-20 08:01:13', NULL, NULL),
 (657, 'agg_max', 'agg_footer', 'agg-footer-6', '305', NULL, NULL, NULL, NULL, 1, '2024-07-21 08:01:13', '2024-07-21 08:01:13', NULL, NULL),
 (658, 'agg_range', 'agg_footer', 'agg-footer-7', '305', NULL, NULL, NULL, NULL, 1, '2024-07-22 08:01:13', '2024-07-22 08:01:13', NULL, NULL),
 (659, 'agg_count_unique_values', 'agg_footer', 'agg-footer-8', '305', NULL, NULL, NULL, NULL, 1, '2024-07-23 08:01:13', '2024-07-23 08:01:13', NULL, NULL),
 (660, 'agg_unique_values', 'agg_footer', 'agg-footer-9', '305', NULL, NULL, NULL, NULL, 1, '2024-07-24 08:01:13', '2024-07-24 08:01:13', NULL, NULL),

 (661, 'Tag', 'row_renderer', 'row-renderer', '306', NULL, NULL, NULL, NULL, 1, '2024-07-25 08:01:13', '2024-07-25 08:01:13', NULL, NULL),
 (662, 'Id', 'row_renderer', 'row-renderer-1', '306', NULL, NULL, NULL, NULL, 1, '2024-07-26 08:01:13', '2024-07-26 08:01:13', NULL, NULL),
 (663, 'Normal', 'row_renderer', 'row-renderer-2', '306', NULL, NULL, NULL, NULL, 1, '2024-07-27 08:01:13', '2024-07-27 08:01:13', NULL, NULL),
 (664, 'Null', 'row_renderer', 'row-renderer-3', '306', NULL, NULL, NULL, NULL, 1, '2024-07-28 08:01:13', '2024-07-28 08:01:13', NULL, NULL),
 (665, 'Icon', 'row_renderer', 'row-renderer-4', '306', NULL, NULL, NULL, NULL, 1, '2024-07-29 08:01:13', '2024-07-29 08:01:13', NULL, NULL),
 (666, 'Row Renderer 06', 'row_renderer', 'row-renderer-10', '306', NULL, NULL, NULL, NULL, 1, '2024-08-04 08:01:13', '2024-08-04 08:01:13', NULL, NULL),
 (667, 'Row Renderer 07', 'row_renderer', 'row-renderer-11', '306', NULL, NULL, NULL, NULL, 1, '2024-08-05 08:01:13', '2024-08-05 08:01:13', NULL, NULL),
 (668, 'Row Renderer 08', 'row_renderer', 'row-renderer-12', '306', NULL, NULL, NULL, NULL, 1, '2024-08-06 08:01:13', '2024-08-06 08:01:13', NULL, NULL),
 (669, 'Row Renderer 09', 'row_renderer', 'row-renderer-13', '306', NULL, NULL, NULL, NULL, 1, '2024-08-07 08:01:13', '2024-08-07 08:01:13', NULL, NULL),
 (670, 'Row Renderer 10', 'row_renderer', 'row-renderer-14', '306', NULL, NULL, NULL, NULL, 1, '2024-08-08 08:01:13', '2024-08-08 08:01:13', NULL, NULL), 

 (681, 'Chart Type 01', 'chart_type', 'chart-type-1', '308', NULL, NULL, NULL, NULL, 1, '2024-08-09 08:01:13', '2024-08-09 08:01:13', NULL, NULL),
 (682, 'Chart Type 02', 'chart_type', 'chart-type-2', '308', NULL, NULL, NULL, NULL, 1, '2024-08-10 08:01:13', '2024-08-10 08:01:13', NULL, NULL),
 (683, 'Chart Type 03', 'chart_type', 'chart-type-3', '308', NULL, NULL, NULL, NULL, 1, '2024-08-11 08:01:13', '2024-08-11 08:01:13', NULL, NULL),
 (684, 'Chart Type 04', 'chart_type', 'chart-type-4', '308', NULL, NULL, NULL, NULL, 1, '2024-08-12 08:01:13', '2024-08-12 08:01:13', NULL, NULL),
 (685, 'Chart Type 05', 'chart_type', 'chart-type-5', '308', NULL, NULL, NULL, NULL, 1, '2024-08-13 08:01:13', '2024-08-13 08:01:13', NULL, NULL),
 (686, 'Chart Type 06', 'chart_type', 'chart-type-6', '308', NULL, NULL, NULL, NULL, 1, '2024-08-14 08:01:13', '2024-08-14 08:01:13', NULL, NULL),
 (687, 'Chart Type 07', 'chart_type', 'chart-type-7', '308', NULL, NULL, NULL, NULL, 1, '2024-08-15 08:01:13', '2024-08-15 08:01:13', NULL, NULL),
 (688, 'Chart Type 08', 'chart_type', 'chart-type-8', '308', NULL, NULL, NULL, NULL, 1, '2024-08-16 08:01:13', '2024-08-16 08:01:13', NULL, NULL),
 (689, 'Chart Type 09', 'chart_type', 'chart-type-9', '308', NULL, NULL, NULL, NULL, 1, '2024-08-17 08:01:13', '2024-08-17 08:01:13', NULL, NULL),
 (690, 'Chart Type 10', 'chart_type', 'chart-type-10', '308', NULL, NULL, NULL, NULL, 1, '2024-08-18 08:01:13', '2024-08-18 08:01:13', NULL, NULL),
 (691, 'Chart Type 11', 'chart_type', 'chart-type-11', '308', NULL, NULL, NULL, NULL, 1, '2024-08-19 08:01:13', '2024-08-19 08:01:13', NULL, NULL),
 (692, 'Chart Type 12', 'chart_type', 'chart-type-12', '308', NULL, NULL, NULL, NULL, 1, '2024-08-20 08:01:13', '2024-08-20 08:01:13', NULL, NULL),
 (693, 'Chart Type 13', 'chart_type', 'chart-type-13', '308', NULL, NULL, NULL, NULL, 1, '2024-08-21 08:01:13', '2024-08-21 08:01:13', NULL, NULL),
 (694, 'Chart Type 14', 'chart_type', 'chart-type-14', '308', NULL, NULL, NULL, NULL, 1, '2024-08-22 08:01:13', '2024-08-22 08:01:13', NULL, NULL),
 (695, 'Chart Type 15', 'chart_type', 'chart-type-15', '308', NULL, NULL, NULL, NULL, 1, '2024-08-23 08:01:13', '2024-08-23 08:01:13', NULL, NULL),
 (696, 'Chart Type 16', 'chart_type', 'chart-type-16', '308', NULL, NULL, NULL, NULL, 1, '2024-08-24 08:01:13', '2024-08-24 08:01:13', NULL, NULL),
 (697, 'Chart Type 17', 'chart_type', 'chart-type-17', '308', NULL, NULL, NULL, NULL, 1, '2024-08-25 08:01:13', '2024-08-25 08:01:13', NULL, NULL),
 (698, 'Chart Type 18', 'chart_type', 'chart-type-18', '308', NULL, NULL, NULL, NULL, 1, '2024-08-26 08:01:13', '2024-08-26 08:01:13', NULL, NULL),
 (699, 'Chart Type 19', 'chart_type', 'chart-type-19', '308', NULL, NULL, NULL, NULL, 1, '2024-08-27 08:01:13', '2024-08-27 08:01:13', NULL, NULL),
 (700, 'Chart Type 20', 'chart_type', 'chart-type-20', '308', NULL, NULL, NULL, NULL, 1, '2024-08-28 08:01:13', '2024-08-28 08:01:13', NULL, NULL)






 --}}