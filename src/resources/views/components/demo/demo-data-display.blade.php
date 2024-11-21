<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Avatars">
        <x-renderer.card title="Avatar Item">
            <div class="grid gap-6 mb-8 md:grid-cols-2 ">
                <div>
                    AvatarItem with Attributes
                    <x-renderer.avatar-item title="Steve Jobs" description="Software Engineer" href="#" avatar="/images/helen.jpeg"></x-renderer.avatar-item>
                </div>
                <div>
                    AvatarItem and GRAY
                    <x-renderer.avatar-item title="Alan Turing" description="Software Engineer" href="#" avatar="/images/hans.jpeg" gray='true'></x-renderer.avatar-item>
                </div>
                <div>
                    Flipped
                    <x-renderer.avatar-item title="Alan Turing" description="Software Engineer" href="#" avatar="/images/hans.jpeg" flipped=1></x-renderer.avatar-item>
                </div>
                <div>
                    Flipped and GRAY
                    <x-renderer.avatar-item title="Alan Turing" description="Software Engineer" href="#" avatar="/images/hans.jpeg" flipped=1 gray='true'></x-renderer.avatar-item>
                </div>
                <div>
                    Vertical
                    <x-renderer.avatar-item title="Alan Turing" description="Software Engineer" href="#" avatar="/images/hans.jpeg" verticalLayout=1></x-renderer.avatar-item>
                </div>
                <div>
                    Vertical and GRAY
                    <x-renderer.avatar-item title="Alan Turing" description="Software Engineer" href="#" avatar="/images/hans.jpeg" verticalLayout=1 gray='true'></x-renderer.avatar-item>
                </div>
                <div>
                    Empty attribute (expected No Name with an anonymous avatar)
                    <x-renderer.avatar-item></x-renderer.avatar-item>
                </div>
                <div>
                    Empty attribute (expected Module icon with text unknown)
                    <x-renderer.avatar-item shape="square" avatar="/images/modules.png" title="Unknown"></x-renderer.avatar-item>
                </div>
            </div>
        </x-renderer.card>
        <x-renderer.card title="Avatar User"> 
            <div class="grid gap-6 mb-8 md:grid-cols-2 ">
                <div>
                    AvatarUser with Slot
                    <x-renderer.avatar-user>{!!Auth::user()!!}</x-renderer.avatar-user>
                </div>
                <div>
                    Empty attribute (expected blank)
                    <x-renderer.avatar-user></x-renderer.avatar-user>
                </div>
                <div>
                    AvatarUser Array (2 users)
                    <x-renderer.avatar-user>[{!!Auth::user()!!}, {!!Auth::user()!!}]</x-renderer.avatar-user>
                </div>
                <div>
                    AvatarUser Array (4 users)
                    <x-renderer.avatar-user>[{!!Auth::user()!!}, {!!Auth::user()!!}, {!!Auth::user()!!}, {!!Auth::user()!!}]</x-renderer.avatar-user>
                </div>
            </div>
        </x-renderer.card>
    </x-renderer.card>
    <x-renderer.card title="Descriptions">
        {{-- <x-renderer.description-group control='111' :prop="['a','b']" :items="['a','b']" />
        <x-feedback.alert message="TODO: HERE WITHOUT DB INVOLVED" type="error" />
        <br />
        <div class="grid grid-cols-12">
            <x-renderer.description label="Name" colName="hello" colSpan=12 :contents="123" />
            <x-renderer.description label="Age" colName="hello" colSpan=6 :contents="60" />
            <x-renderer.description label="Status" colName="hello" colSpan=6 :contents="456" />
        </div> --}}
    </x-renderer.card>
    {{-- <x-renderer.card title="Another component here"> --}}
    {{-- </x-renderer.card> --}}
    <x-renderer.card title="Tables 1">
        <x-renderer.card title="Table with Data and maxH= { { false } }">
            In dataSource: rowDescription="This is an example of a rowDescription"
            <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" maxH={{false}} />
        </x-renderer.card>
        <br />
        <x-renderer.card title="Table with Data and No. and dataHeader">
            showNo= { { true } }
            <x-renderer.table headerTop={{12 * 16}} maxH={{14 * 16}} :columns="$tableColumns" :dataHeader="$tableDataHeader" :dataSource="$tableDataSource" showNo="{{true}}" />
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
            <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNoR="{{true}}" groupBy="client" maxH={{false}} />
        </x-renderer.card>
        <br />
        <x-renderer.card title="Table with Data and No.(Most Right) and GroupBy (Case Insensitive) but keep original order">
            showNoR= { { true } }
            groupBy="status" groupByLength=100 groupKeepOrder={ { true } }
            <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNoR="{{true}}" groupBy="status" groupByLength=100 groupKeepOrder={{true}} />
        </x-renderer.card>
        <br />
        <x-renderer.card title="Table with cell_class, cell_title, cell_href, cell_div_class, and rotate45">
            <x-renderer.table :columns="$tableColumnsForRegister" :dataSource="$tableDataSourceForRegister" showNo="{{true}}" rotate45Width={{200}} />
        </x-renderer.card>
        <x-renderer.card title="Table with showPaginationTop, topLeftControl, topCenterControl, topRightControl, bottomLeftControl, bottomCenterControl, bottomRightControl">
            @php
             $tl = "<x-renderer.tag>Top Left</x-renderer.tag>" ;
             $tc = "<x-renderer.tag>Top Center</x-renderer.tag>"; 
             $tr = "<x-renderer.tag>Top Right</x-renderer.tag>"; 
             $bl = "<x-renderer.tag>Bottom Left</x-renderer.tag>"; 
             $bc = "<x-renderer.tag>Bottom Center</x-renderer.tag>"; 
             $br = "<x-renderer.tag>Bottom Right</x-renderer.tag>"; 
            @endphp
            <x-renderer.table :columns="$tableColumnsForRegister" :dataSource="$tableDataSourceForRegister" showNo="{{true}}" rotate45Width={{200}}
                showPaginationTop="true"
                topLeftControl="{!!$tl!!}" 
                topCenterControl="{!!$tc!!}" 
                topRightControl="{!!$tr!!}" 
                bottomLeftControl="{!!$bl!!}" 
                bottomCenterControl="{!!$bc!!}" 
                bottomRightControl="{!!$br!!}" 
            />
        </x-renderer.card>
    </x-renderer.card>
</div>
