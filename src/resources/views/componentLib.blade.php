@extends('layouts.applean')
@section('content')

<br />
<br />
<div class="grid gap-6 mb-8 md:grid-cols-2">
    <x-renderer.card title="Tables">
        <x-renderer.card title="Table with Data">
            <x-renderer.table :columns="$tableColumns" :dataSource="$tableDatasource" />
        </x-renderer.card>
        <br />
        <x-renderer.card title="Empty Table">
            <x-renderer.table :columns="$tableColumns" :dataSource="[]" />
        </x-renderer.card>
    </x-renderer.card>
    <x-renderer.card title="Tags">
        <x-renderer.tag color="slate">slate</x-renderer.tag>
        <x-renderer.tag color="zinc">zinc</x-renderer.tag>
        <x-renderer.tag color="neutral">neutral</x-renderer.tag>
        <x-renderer.tag color="stone">stone</x-renderer.tag>
        <x-renderer.tag color="amber">amber</x-renderer.tag>
        <br />
        <x-renderer.tag color="yellow">yellow</x-renderer.tag>
        <x-renderer.tag color="lime">lime</x-renderer.tag>
        <x-renderer.tag color="emerald">emerald</x-renderer.tag>
        <x-renderer.tag color="teal">teal</x-renderer.tag>
        <x-renderer.tag color="cyan">cyan</x-renderer.tag>
        <br />
        <x-renderer.tag color="sky">sky</x-renderer.tag>
        <x-renderer.tag color="blue">blue</x-renderer.tag>
        <x-renderer.tag color="indigo">indigo</x-renderer.tag>
        <x-renderer.tag color="violet">violet</x-renderer.tag>
        <x-renderer.tag color="purple">purple</x-renderer.tag>
        <br />
        <x-renderer.tag color="fuchsia">fuchsia</x-renderer.tag>
        <x-renderer.tag color="pink">pink</x-renderer.tag>
        <x-renderer.tag color="rose">rose</x-renderer.tag>
        <x-renderer.tag color="green">green</x-renderer.tag>
        <x-renderer.tag color="orange">orange</x-renderer.tag>
        <br />
        <x-renderer.tag color="red">red</x-renderer.tag>
        <x-renderer.tag color="gray">gray</x-renderer.tag>
        <x-renderer.tag></x-renderer.tag>
    </x-renderer.card>
    <x-renderer.card title="Alerts">
        <x-feedback.alert type="success" title="Success" message="Hello Success"></x-feedback.alert>
        <x-feedback.alert type="info" title="Info" message="Hello Info"></x-feedback.alert>
        <x-feedback.alert type="warning" title="Warning" message="Hello Warning"></x-feedback.alert>
        <x-feedback.alert type="error" title="Error" message="Hello Error"></x-feedback.alert>
    </x-renderer.card>
</div>

@endsection