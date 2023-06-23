<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Modes Control">
        {{-- @dump($dataSource) --}}
        <form action="" method="GET"> </form>
        @dump($_GET)
        <x-renderer.card title="dataSource=[name1 => [], name2=>[]], itemsSelected=[name1 => id1, name2=> id2]">
            {{-- <x-renderer.parameter-control :dataSource="$dataSource" :itemsSelected="$itemsSelected" /> --}}
        </x-renderer.card>

        <x-renderer.card title="dataSource=[name1 => [], name2=>[]], itemsSelected=[name1 => id1, name2=> id2], hiddenItems=[name2]">
            {{-- <x-renderer.parameter-control :dataSource="$dataSource" :itemsSelected="$itemsSelected" :hiddenItems="['name_2']" /> --}}
        </x-renderer.card>
    </x-renderer.card>
</div>
