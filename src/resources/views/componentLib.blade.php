@extends('layouts.applean')
@section('content')

<br />
<br />
<x-renderer.card title="Feedback">
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <x-renderer.card title="">
            @dump($_GET)
            <form action="" method="GET">
                <x-renderer.comment name="comment_1" type="department" id="1" readonly={{true}} :dataComment="$dataComment"></x-renderer.comment>
                <x-renderer.comment name="comment_1" type="department" id="1" readonly={{false}} :dataComment="$dataComment"></x-renderer.comment>
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
        </x-renderer.card>
        <x-renderer.card title="Grids">
            Grid colSpan=4 itemRenderer=x-renderer.avatar-name
            <x-renderer.grid colSpan="4" :items="$gridDataSource" itemRenderer="x-renderer.avatar-name"></x-renderer.grid>
            <br/>
            Grid colSpan=4 itemRenderer=x-renderer.avatar-name groupBy=name
            <x-renderer.grid colSpan="4" :items="$gridDataSource" itemRenderer="x-renderer.avatar-name" groupBy="name"></x-renderer.grid>
        </x-renderer.card>

        <x-renderer.card title="AvatarName">
            AvatarName with Attributes
            <x-renderer.avatar-name title="Fortune Truong" description="Software Engineer" href="http://www.google.com"></x-renderer.avatar-name>
            AvatarName with Slot
            <x-renderer.avatar-name>{
                "name":"Thuc Vo", 
                "position_rendered":"Software Tester",
                "href":"http://www.google.com",
                "avatar":"https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
            }</x-renderer.avatar-name>
            Empty attribute
            <x-renderer.avatar-name></x-renderer.avatar-name>

        </x-renderer.card>

        <x-renderer.card title="Controls">
            Text
            <x-renderer.editable.text name="text1">Hello</x-renderer.editable.text>
            Number
            <x-renderer.editable.number name="number1">2606.1988</x-renderer.editable.number>
            Dropdown
            <x-renderer.editable.dropdown name="dropdown1" :cbbDataSource='["", "true"]'>true</x-renderer.editable.dropdown>
            Dropdown with sortBy='value'
            <x-renderer.editable.dropdown name="dropdown2" :cbbDataSource='["3", "2", "1"]' sortBy='value'>true</x-renderer.editable.dropdown>
            Status
            <br/>
            <x-renderer.status>unknown_status</x-renderer.status><br/>
            <x-renderer.status>new</x-renderer.status><br/>
            <x-renderer.status>assigned</x-renderer.status><br/>
            <x-renderer.status>closed</x-renderer.status><br/>
            <br/>
            Toggle
            <x-renderer.switch color="bg-blue-900" content="My text here" />
            
        </x-renderer.card>

        <x-renderer.card title="Tables">
            <x-renderer.card title="Table with Data">
                In dataSource: rowDescription="This is an example of a rowDescription"
                <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
            </x-renderer.card>
            <br />
            <x-renderer.card title="Table with Data and No.">
                showNo= { { true } }
                <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNo="{{true}}" />
            </x-renderer.card>
            <br />

            <x-renderer.card title="Table with Data and No.(Most Right) and GroupBy (Case Insensitive)">
                showNoR= { { true } }
                groupBy="client"

                <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNoR="{{true}}" groupBy="client" />
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

        <x-renderer.card title="Tags">
            <x-renderer.tag color="slate">slate</x-renderer.tag><span></span>
            <x-renderer.tag color="zinc">zinc</x-renderer.tag><span></span>
            <x-renderer.tag color="neutral">neutral</x-renderer.tag><span></span>
            <x-renderer.tag color="stone">stone</x-renderer.tag><span></span>
            <x-renderer.tag color="amber">amber</x-renderer.tag><span></span>
            <br />
            <x-renderer.tag color="yellow">yellow</x-renderer.tag><span></span>
            <x-renderer.tag color="lime">lime</x-renderer.tag><span></span>
            <x-renderer.tag color="emerald">emerald</x-renderer.tag><span></span>
            <x-renderer.tag color="teal">teal</x-renderer.tag><span></span>
            <x-renderer.tag color="cyan">cyan</x-renderer.tag><span></span>
            <br />
            <x-renderer.tag color="sky">sky</x-renderer.tag><span></span>
            <x-renderer.tag color="blue">blue</x-renderer.tag><span></span>
            <x-renderer.tag color="indigo">indigo</x-renderer.tag><span></span>
            <x-renderer.tag color="violet">violet</x-renderer.tag><span></span>
            <x-renderer.tag color="purple">purple</x-renderer.tag><span></span>
            <br />
            <x-renderer.tag color="fuchsia">fuchsia</x-renderer.tag><span></span>
            <x-renderer.tag color="pink">pink</x-renderer.tag><span></span>
            <x-renderer.tag color="rose">rose</x-renderer.tag><span></span>
            <x-renderer.tag color="green">green</x-renderer.tag><span></span>
            <br />
            <x-renderer.tag color="orange">orange</x-renderer.tag><span></span>
            <x-renderer.tag color="red">red</x-renderer.tag><span></span>
            <x-renderer.tag color="gray">gray</x-renderer.tag><span></span>
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
            <br />
            <br />
            <x-renderer.button size="xs" type="primary">Primary</x-renderer.button>
            <x-renderer.button size="xs" type="secondary">secondary</x-renderer.button>
            <x-renderer.button size="xs" type="default">default</x-renderer.button>
            <x-renderer.button size="xs" type="success">success</x-renderer.button>
            <x-renderer.button size="xs" type="danger">danger</x-renderer.button>
            <x-renderer.button size="xs" type="warning">warning</x-renderer.button>
            <x-renderer.button size="xs" type="info">info</x-renderer.button>
            <x-renderer.button size="xs" type="dark">dark</x-renderer.button>
            <x-renderer.button size="xs" type="link">link</x-renderer.button>
            <br />
            <br />
            <x-renderer.button size="xs" outline=true type="primary">Primary</x-renderer.button>
            <x-renderer.button size="xs" outline=true type="secondary">secondary</x-renderer.button>
            <x-renderer.button size="xs" outline=true type="default">default</x-renderer.button>
            <x-renderer.button size="xs" outline=true type="success">success</x-renderer.button>
            <x-renderer.button size="xs" outline=true type="danger">danger</x-renderer.button>
            <x-renderer.button size="xs" outline=true type="warning">warning</x-renderer.button>
            <x-renderer.button size="xs" outline=true type="info">info</x-renderer.button>
            <x-renderer.button size="xs" outline=true type="dark">dark</x-renderer.button>
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
        <x-renderer.card title="Tabs">
            <x-navigation.tabs>

            </x-navigation.tabs>
        </x-renderer.card>
    </div>
</x-renderer.card>

@endsection
