<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Cards">
        <x-renderer.card title="Card 1">
        </x-renderer.card>
        <br/>
        <x-renderer.card title="Card 2">
        </x-renderer.card>
    </x-renderer.card>
    <x-renderer.card title="Tabs">
        <x-renderer.tab-pane :tabs="$tabData1" >
            Content 1
        </x-renderer.tab-pane>
        <br/>
        <x-renderer.tab-pane :tabs="$tabData2">
            Content 2
        </x-renderer.card>
    </x-renderer.card>
</div>