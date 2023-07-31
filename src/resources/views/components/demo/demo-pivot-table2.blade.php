<x-renderer.heading level=5>Raw Data</x-renderer.heading>
<x-renderer.table :columns="$columns" :dataSource="$dataSource"/>



<x-renderer.heading level=5>Pivot by apple_store_empty_input</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_empty_input" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_delete_fields_database</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_delete_fields_database" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_product_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_product_per_date" :dataSource="$dataSource"/>

 <x-renderer.heading level=5>Pivot by apple_store_category_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_category_per_date" :dataSource="$dataSource"/>

{{--
<x-renderer.heading level=5>Pivot by apple_store_empty_row_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_empty_row_field" :dataSource="$dataSource"/>

<div class="grid grid-cols-12 gap-2">
<div class="col-span-6">
    <x-renderer.heading level=5>Pivot by apple_store_minimum_quantity_column_field</x-renderer.heading>
    <x-renderer.report.pivot-table key="apple_store_minimum_quantity_column_field" :dataSource="$dataSource"/>
</div>

<div class="col-span-6">
    <x-renderer.heading level=5>Pivot by apple_store_minimum_quantity_column_field_equal_3</x-renderer.heading>
    <x-renderer.report.pivot-table key="apple_store_minimum_quantity_column_field_equal_3" :dataSource="$dataSource"/>
</div>
</div>

<x-renderer.heading level=5>Pivot by apple_store_duplicate_value_in_column_field (date)</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_duplicate_value_in_column_field" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_duplicate_value_in_column_field_many_objects (add product)</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_duplicate_value_in_column_field_many_objects" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_duplicate_field_column_field_value_index_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_duplicate_field_column_field_value_index_field" :dataSource="$dataSource"/>


<x-renderer.heading level=5>Pivot by apple_store_test_code</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_test_code" :dataSource="$dataSource"/> --}}
