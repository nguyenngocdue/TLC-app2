<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="AvatarName">
        <div class="grid gap-6 mb-8 md:grid-cols-2 ">
            <div>
                AvatarName with Attributes
                <x-renderer.avatar-name title="Fortune Truong" description="Software Engineer" href="http://www.google.com"></x-renderer.avatar-name>
                <br />
                AvatarName with Slot
                <x-renderer.avatar-name>{
                    "name":"Thuc Vo",
                    "position_rendered":"Software Tester",
                    "href":"http://www.google.com",
                    }</x-renderer.avatar-name>
            </div>
            <div>
                AvatarName with gray= { { true } }
                <x-renderer.avatar-name title="Fortune Truong" description="Software Engineer" href="http://www.google.com" gray='true'></x-renderer.avatar-name>
                <br />
                Empty attribute
                <x-renderer.avatar-name></x-renderer.avatar-name>
                <br />
            </div>
        </div>
    </x-renderer.card>
    <x-renderer.card title="Descriptions">
        <x-renderer.description-group control='111' :prop="['a','b']" :items="['a','b']" />
        <x-feedback.alert message="TODO: HERE WITHOUT DB INVOLVED" type="error" />
        <br />
        <div class="grid grid-cols-12">
            <x-renderer.description label="Name" colName="hello" colSpan=12 :contents="123" />
            <x-renderer.description label="Age" colName="hello" colSpan=6 :contents="60" />
            <x-renderer.description label="Status" colName="hello" colSpan=6 :contents="456" />
        </div>
    </x-renderer.card>
    <x-renderer.card title="Tables 1">
        <x-renderer.card title="Table with Data">
            In dataSource: rowDescription="This is an example of a rowDescription"
            <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
        </x-renderer.card>
        <br />
        <x-renderer.card title="Table with Data and No. and dataHeader">
            showNo= { { true } }z
            <x-renderer.table :columns="$tableColumns" :dataHeader="$tableDataHeader" :dataSource="$tableDataSource" showNo="{{true}}" />
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
    <x-renderer.card title="Tables 2">
        <x-renderer.card title="Table with Data and No.(Most Right) and GroupBy (Case Insensitive)">
            showNoR= { { true } }
            groupBy="client" groupByLength=1
            <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNoR="{{true}}" groupBy="client" />
        </x-renderer.card>
        <br />
        <x-renderer.card title="Table with Data and No.(Most Right) and GroupBy (Case Insensitive) but keep original order">
            showNoR= { { true } }
            groupBy="status" groupByLength=100 groupKeepOrder={ { true }}
            <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNoR="{{true}}" groupBy="status" groupByLength=100 groupKeepOrder={{true}}/>
        </x-renderer.card>
        <br />
    </x-renderer.card>
</div>
