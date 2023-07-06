<x-renderer.heading level=5>Raw Data</x-renderer.heading>
{{-- @dump($dataSource) --}}
<x-renderer.table :columns="$columns" :dataSource="$dataSource"/>



<x-renderer.heading level=5>Pivot by empty_input</x-renderer.heading>
<x-renderer.report.pivot-table key="empty_input" :dataSource="$dataSource"/>


<x-renderer.heading level=5>Pivot by apple_store_delete_fields_database</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_delete_fields_database" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_product_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_product_per_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_category_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_category_per_date" :dataSource="$dataSource"/>


<x-renderer.heading level=5>Pivot by apple_store_empty_row_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_empty_row_field" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_minimum_quantity_column_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_minimum_quantity_column_field" :dataSource="$dataSource"/>


<x-renderer.heading level=5>Pivot by apple_store_minimum_quantity_column_field_equal_3</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_minimum_quantity_column_field_equal_3" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_duplicate_value_in_column_field (date)</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_duplicate_value_in_column_field" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_duplicate_value_in_column_field_many_objects (add product)</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_duplicate_value_in_column_field_many_objects" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_duplicate_fields_column_field_&&_value_index_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_duplicate_field_column_field_value_index_field" :dataSource="$dataSource"/>


<x-renderer.heading level=5>Pivot by apple_store_test_code</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_test_code" :dataSource="$dataSource"/>
