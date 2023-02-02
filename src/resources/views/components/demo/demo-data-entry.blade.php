<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Textbox & Number">
        Textbox
        <x-renderer.editable.text name="text1">Hello</x-renderer.editable.text>
        Number
        <x-renderer.editable.number name="number1">2606.1988</x-renderer.editable.number>
    </x-renderer.card>
    <x-renderer.card title="Toggle">
        Toggle FALSE
        <x-controls.toggle name='hello1' value=0></x-controls.toggle>
        <br />
        Toggle TRUE
        <x-controls.toggle name='hello2' value=1></x-controls.toggle>
        <br />
    </x-renderer.card>
    <x-renderer.card title="Dropdown">
        Dropdown
        <x-renderer.editable.dropdown name="dropdown1" :cbbDataSource='["", "true"]'>true</x-renderer.editable.dropdown>
        Dropdown with sortBy='value'
        <x-renderer.editable.dropdown name="dropdown2" :cbbDataSource='["3", "2", "1"]' sortBy='value'>true</x-renderer.editable.dropdown>
        Dropdown with cbbDS (Dynamic cbbDataSource loaded from CELL as {'value' => selected, 'cbbDS' => ['', 'a','b',c']})
        @dump($dropdownCell)
        <x-renderer.editable.dropdown name="dropdown2" :cell="$dropdownCell"></x-renderer.editable.dropdown>
    </x-renderer.card>
    <x-renderer.card title="Dropdown Multi">
    </x-renderer.card>
</div>