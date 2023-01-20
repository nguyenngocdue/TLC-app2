@extends('layouts.applean')
@section('content')

<br />
<br />
<x-renderer.card title="Feedback">
    <div class="grid gap-6 mb-8 md:grid-cols-2 ">
        <x-renderer.card title="Widgets">
            <div class ="grid grid-cols-12">
                <x-renderer.description label="Name" colName="hello" colSpan=12 :contents="123"/>
                <x-renderer.description label="Age" colName="hello" colSpan=6 :contents="60"/>
                <x-renderer.description label="Status" colName="hello" colSpan=6 :contents="456"/>
            </div>
            <br/>
            <x-renderer.description-group control='111' :prop="['a','b']" :items="['a','b']" />
            <div class="mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-2">
                <x-renderer.report.widget/>
                <x-renderer.report.widget title="Total Clients" figure="1234"/>
                <x-renderer.report.widget title="Account balance" figure="$ 46,760.89"/>
                <x-renderer.report.widget title="Pending contacts" figure="35"/>
                <button @click="openModal('modal1')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Open Modal 1
                </button>
                <button @click="openModal('modal2')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Open Modal 2
                </button>
                <x-feedback.modal1 id="modal1" title="Hello 1" content="What is this"/>
                <x-feedback.modal1 id="modal2" title="Hello 2" content="I am good"/>
                <button @click="openModal('modal3')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Open Modal Extended 3
                </button>
                <button @click="openModal('modal4')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Open Modal Extended 4
                </button>
                <x-feedback.modal-extended id="modal3" title="Hello 3" content="What is this"/>
                <x-feedback.modal-extended id="modal4" title="Hello 4" content="I am good"/>
            </div>
        </x-renderer.card>
        <x-renderer.card title="Typography">
            <x-renderer.heading level=1>Heading Level 1</x-renderer.heading>
            <x-renderer.heading level=2>Heading Level 2</x-renderer.heading>
            <x-renderer.heading level=3>Heading Level 3</x-renderer.heading>
            <x-renderer.heading level=4>Heading Level 4</x-renderer.heading>
            <x-renderer.heading level=5>Heading Level 5</x-renderer.heading>
            <x-renderer.heading>Heading Level 6: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</x-renderer.heading>
        </x-renderer.card>
        <x-renderer.card title="Attachments">
            @dump($_GET)
            <form action="" method="GET">
                <x-renderer.card title="attachmentData = [[]], not entering other attributes.  ">
                    <x-renderer.attachment :attachmentData="[]" />
                </x-renderer.card>
                <x-renderer.card title="readonly ={ { true } }, destroyable ={ { true } }, showToBeDeleted  ={ { true } }">
                    <x-renderer.attachment readonly={{true}} destroyable={{true}} showToBeDeleted={{true}} :attachmentData="$attachmentData" />
                </x-renderer.card>
                <br />
                <x-renderer.card title="readonly = { { false} }, destroyable = { { false} }, showToBeDeleted = { { false} }, categoryName={ {attachment_2} } ">
                    <x-renderer.attachment readonly={{false}} destroyable={{false}} categoryName="attachment_2" showToBeDeleted={{false}} :attachmentData="$attachmentData" />
                </x-renderer.card>
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
        </x-renderer.card>


        <x-renderer.card title="Comment">
            @dump($_GET)
            <form action="" method="GET">
                <x-renderer.card title="dataComment=[], not entering other attributes.">
                    <x-renderer.comment :dataComment="[]" />
                </x-renderer.card>
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
            <br />
            @dump($_GET)
            <form action="" method="GET">
                <x-renderer.card title="readonly ={ { true } }, destroyable ={ { true } }, showToBeDeleted  ={ { true } }, name={ { comment_1 } }">
                    <x-renderer.comment readonly={{true}} destroyable={{true}} showToBeDeleted={{true}} name="comment_1" type="department" id="1" :dataComment="$dataComment" />
                </x-renderer.card>
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
            <br />
            @dump($_GET)
            <form action="" method="GET">
                <x-renderer.card title="readonly={ { false } }, destroyable={ { false } }, showToBeDeleted={ { false } } name={ { comment_1 } }">
                    <x-renderer.comment readonly={{false}} destroyable={{false}} showToBeDeleted={{false}} name="comment_1" type="department" id="1" :dataComment="$dataComment" />
                </x-renderer.card>
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
            <br />
            @dump($_GET)
            <form action="" method="GET">
                <x-renderer.card title="readonly={ { false } } showToBeDeleted={ { false } }, destroyable={ { true } }, attachmentData={ { $attachmentData } } name={ { comment_1 } }">
                    <x-renderer.comment readonly={{false}} showToBeDeleted={{false}} destroyable={{true}} name="comment_1" type="department" id="1" :dataComment="$dataComment" :attachmentData="$attachmentData" />
                </x-renderer.card>
                <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
            </form>
        </x-renderer.card>



        <x-renderer.card title="Grids">
            Grid colSpan=4 itemRenderer=x-renderer.avatar-name
            <x-renderer.grid colSpan="4" :items="$gridDataSource" itemRenderer="x-renderer.avatar-name"></x-renderer.grid>
            <br />
            Grid colSpan=4 itemRenderer=x-renderer.avatar-name groupBy=name 
            <x-renderer.grid colSpan="4" :items="$gridDataSource" itemRenderer="x-renderer.avatar-name" groupBy="name"></x-renderer.grid>
        </x-renderer.card>

        <x-renderer.card title="Images">
            <x-renderer.image title='A customizable title'></x-renderer.image>
            <x-renderer.image w=40 title='Width: 40'></x-renderer.image>
            <x-renderer.image w=60 title='Width: 60'></x-renderer.image>
            <x-renderer.image w=80 title='Width: 80'></x-renderer.image>
            With HREF
            <x-renderer.image title='Go to Google' href='http://www.google.com'></x-renderer.image>
        </x-renderer.card>

        <x-renderer.card title="AvatarName">
            AvatarName with Attributes
            <x-renderer.avatar-name 
                title="Fortune Truong" 
                description="Software Engineer" 
                href="http://www.google.com"
            ></x-renderer.avatar-name>
            <br/>
            AvatarName with Slot
            <x-renderer.avatar-name>{
                "name":"Thuc Vo",
                "position_rendered":"Software Tester",
                "href":"http://www.google.com",
            }</x-renderer.avatar-name>
            <br/>
            AvatarName with gray= { { true } } 
            <x-renderer.avatar-name 
                title="Fortune Truong" 
                description="Software Engineer" 
                href="http://www.google.com"
                gray='true'
            ></x-renderer.avatar-name>
            <br/>
            Empty attribute
            <x-renderer.avatar-name></x-renderer.avatar-name>
            <br/>
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
            <br />
            <x-renderer.status>unknown_status</x-renderer.status><br />
            <x-renderer.status>new</x-renderer.status><br />
            <x-renderer.status>assigned</x-renderer.status><br />
            <x-renderer.status>closed</x-renderer.status><br />
            <br />
            Toggle FALSE
            <x-controls.toggle colName='hello' value=0 label="hello"></x-controls.toggle>
            <br />
            Toggle TRUE
            <x-controls.toggle colName='hello' value=1 label="hello"></x-controls.toggle>
            <br />

        </x-renderer.card>

        <x-renderer.card title="Tables">
            <x-renderer.card title="Table with Data">
                In dataSource: rowDescription="This is an example of a rowDescription"
                <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
            </x-renderer.card>
            <br />
            <x-renderer.card title="Table with Data and No. and dataHeader">
                showNo= { { true } }
                <x-renderer.table :columns="$tableColumns" :dataHeader="$tableDataHeader" :dataSource="$tableDataSource" showNo="{{true}}" />
            </x-renderer.card>
            <br />

            <x-renderer.card title="Table with Data and No.(Most Right) and GroupBy (Case Insensitive)">
                showNoR= { { true } }
                groupBy="client" groupByLength=1

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
            <x-renderer.table showNo=true :columns="$tagColumns" :dataSource="$tagDataSource"/>

            <x-renderer.tag color="slate" colorIndex=900>slate</x-renderer.tag><span></span>
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
            <x-renderer.card title="Tab 1">
                <x-navigation.tabs :tabs="$tabData1" />
            </x-renderer.card>
            
            <x-renderer.card title="Tab 2">
                <x-navigation.tabs :tabs="$tabData2"/>
            </x-renderer.card>
            {{-- <br />
            <x-renderer.card title="Tab 1">
                <x-navigation.tabs-1 />
            </x-renderer.card>
            <br />
            <x-renderer.card title="Tab 2">
                <x-navigation.tabs-2 />
            </x-renderer.card>
            --}}
        </x-renderer.card>
    </div>
</x-renderer.card>

@endsection
