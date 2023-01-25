<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Data Tables">
        <x-renderer.card title="Table with Data">
            @dump($_GET)
            <form action="" method="GET">
                @csrf
                <x-renderer.table showNo={{true}} :columns="$tableEditableColumns" :dataSource="$tableDataSource" />
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
        </x-renderer.card>
    </x-renderer.card>
</div>