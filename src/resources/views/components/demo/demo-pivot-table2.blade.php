<x-renderer.heading level=5>Raw Data</x-renderer.heading>
{{-- @dump($dataSource) --}}
<x-renderer.table :columns="$columns" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_draw_row_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_draw_row_field" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_product_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_product_per_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_category_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_category_per_date" :dataSource="$dataSource"/>


<x-renderer.heading level=5>Pivot by apple_store_empty_row_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_empty_row_field" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_empty_row_field_minimum_quantity_column_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_empty_row_field_minimum_quantity_column_field" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_duplicate_value_in_column_field (date)</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_duplicate_value_in_column_field" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_duplicate_value_in_column_field_test1</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_duplicate_value_in_column_field_test1" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_product_date_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_product_date_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_test_display</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_test_display" :dataSource="$dataSource"/>
