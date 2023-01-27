<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Data Tables">
        <x-renderer.card title="Table with Data">
            @dump($_GET['table_00'] ?? "[]")
            <form method="GET">
                @csrf
                <x-renderer.table tableName='table_00' showNo={{true}} :columns="$tableEditableColumns" :dataSource="$tableDataSource" />
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
        </x-renderer.card>
    </x-renderer.card>
    <x-renderer.card title="Data Tables">
        <x-renderer.card title="Table with Data">
            @dump($_GET['table_01'] ?? "[]")
            <form method="GET">
                @csrf
                <x-renderer.table tableName='table_01' showNo={{true}} :columns="$tableEditableColumns" :dataSource="$tableDataSource" />
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
        </x-renderer.card>
    </x-renderer.card>
</div>