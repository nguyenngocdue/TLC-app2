<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Cards">
        <x-renderer.card title="Card 1">
        </x-renderer.card>
        <br/>
        <x-renderer.card title="Card 2">
        </x-renderer.card>
    </x-renderer.card>
    <x-renderer.card title="Tabs">
        <x-renderer.card title="Tab 1">
            <x-navigation.tabs :tabs="$tabData1" />
        </x-renderer.card>
        <br/>
        <x-renderer.card title="Tab 2">
            <x-navigation.tabs :tabs="$tabData2"/>
        </x-renderer.card>
    </x-renderer.card>
</div>