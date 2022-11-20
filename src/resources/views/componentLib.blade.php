@extends('layouts.applean')
@section('content')

<br />
<br />
<x-renderer.card title="Feedback">
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <x-renderer.card title="Placeholder">
            AvatarName with Attributes
            <x-renderer.avatar-name title="Fortune Truong" description="Software Engineer">?</x-renderer.avatar-name>
            AvatarName with Slot
            <x-renderer.avatar-name>{"name":"Thuc Vo", "position_rendered":"Software Tester"}</x-renderer.avatar-name>
            Empty attribute
            <x-renderer.avatar-name>?</x-renderer.avatar-name>

        </x-renderer.card>

        <x-renderer.card title="Controls">
            Text
            <x-renderer.editable.text name="text1">Hello</x-renderer.editable.text>
            Number
            <x-renderer.editable.number name="number1">2606.1988</x-renderer.editable.number>
            Dropdown
            <x-renderer.editable.dropdown name="dropdown1" :cbbDataSource='["", "true"]'>true</x-renderer.editable.dropdown>
            Dropdown with sortBy
            <x-renderer.editable.dropdown name="dropdown2" :cbbDataSource='["3", "2", "1"]' sortBy='value'>true</x-renderer.editable.dropdown>
        </x-renderer.card>

        <x-renderer.card title="Tables">
            <x-renderer.card title="Table with Data">
                <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
            </x-renderer.card>
            <br />
            <x-renderer.card title="Table with Data and No.">
                <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNo="{{true}}" />
            </x-renderer.card>
            <br />
            <x-renderer.card title="Table with Data and No. and GroupBy (Case Insensitive)">
                <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNo="{{true}}" groupBy="client" />
            </x-renderer.card>
            <br />
            <x-renderer.card title="Empty Table">
                <x-renderer.table :columns="$tableColumns" :dataSource="[]" />
            </x-renderer.card>
            <br />
            <x-renderer.card title="Missing Columns">
                <x-renderer.table />
            </x-renderer.card>
            <br />
            <x-renderer.card title="Missing DataSource">
                <x-renderer.table :columns="$tableColumns" />
            </x-renderer.card>
        </x-renderer.card>

        <x-renderer.card title="Editable Tables">
            <x-renderer.card title="Table with Data">
                @dump($_GET)
                <form action="" method="GET">
                    @csrf
                    <x-renderer.table showNo={{true}} :columns="$tableEditableColumns" :dataSource="$tableDataSource" />
                    <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
                </form>
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
            <br />
            <br />
            Empty attributes:<br />
            <x-renderer.tag></x-renderer.tag>
        </x-renderer.card>

        <x-renderer.card title="Buttons">
            <x-renderer.button type="primary">Primary</x-renderer.button>
            <x-renderer.button type="secondary">secondary</x-renderer.button>
            <x-renderer.button type="default">default</x-renderer.button>
            <x-renderer.button type="success">success</x-renderer.button>
            <x-renderer.button type="danger">danger</x-renderer.button>
            <x-renderer.button type="warning">warning</x-renderer.button>
            <x-renderer.button type="info">info</x-renderer.button>
            <x-renderer.button type="dark">dark</x-renderer.button>
            <x-renderer.button type="link">link</x-renderer.button>
            <br />
            <br />
            <x-renderer.button outline=true type="primary">Primary</x-renderer.button>
            <x-renderer.button outline=true type="secondary">secondary</x-renderer.button>
            <x-renderer.button outline=true type="default">default</x-renderer.button>
            <x-renderer.button outline=true type="success">success</x-renderer.button>
            <x-renderer.button outline=true type="danger">danger</x-renderer.button>
            <x-renderer.button outline=true type="warning">warning</x-renderer.button>
            <x-renderer.button outline=true type="info">info</x-renderer.button>
            <x-renderer.button outline=true type="dark">dark</x-renderer.button>
        </x-renderer.card>
        <x-renderer.card title="Alerts">
            <x-feedback.alert type="success" message="Hello Success"></x-feedback.alert>
            <x-feedback.alert type="info" message="Hello Info"></x-feedback.alert>
            <x-feedback.alert type="warning" message="Hello Warning"></x-feedback.alert>
            <x-feedback.alert type="error" message="Hello Error"></x-feedback.alert>
            <br />
            <br />
            Empty attributes:
            <x-feedback.alert />
        </x-renderer.card>

    </div>
</x-renderer.card>

@endsection