<x-renderer.heading level=5>Raw Data</x-renderer.heading>
{{-- @dump($dataSource) --}}
<x-renderer.table :columns="$columns" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_test_display</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_test_display" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_draw_row_field</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_draw_row_field" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_product_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_product_per_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_category_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_category_per_date" :dataSource="$dataSource"/>
