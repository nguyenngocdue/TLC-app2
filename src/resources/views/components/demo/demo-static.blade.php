<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Widgets">
        <div class="grid gap-6 mb-8 md:grid-cols-2 ">
            <x-renderer.report.widget/>
            <x-renderer.report.widget title="Total Clients" figure="1234"/>
            <x-renderer.report.widget title="Account balance" figure="$ 46,760.89"/>
            <x-renderer.report.widget title="Pending contacts" figure="35"/>
            <x-renderer.report.widget title="Account balance" figure="$ 46,760.89"/>
            <x-renderer.report.widget title="Pending contacts" figure="35"/>
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
    <x-renderer.card title="Images">
        <div class="grid gap-6 mb-8 md:grid-cols-6 ">
            <span>
                <x-renderer.image w=40 title='Width: 40'></x-renderer.image>
                Width: 40
            </span>
            <span>
                <x-renderer.image w=60 title='Width: 60'></x-renderer.image>
                Width: 60
            </span>
            <span>
                <x-renderer.image w=80 title='Width: 80'></x-renderer.image>
                Width: 80
            </span>
            <span>
                <x-renderer.image title='A custom title'></x-renderer.image>
                With title
            </span>
            <span>
                <x-renderer.image title='Go to Google' href='http://www.google.com'></x-renderer.image>
                With HREF
            </span>
        </div>
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
    <x-renderer.card title="Statuses">
        Status
        <br />
        <x-renderer.status>unknown_status</x-renderer.status><br />
        <x-renderer.status>new</x-renderer.status><br />
        <x-renderer.status>assigned</x-renderer.status><br />
        <x-renderer.status>closed</x-renderer.status><br />
        <br />
    </x-renderer.card>
    <x-renderer.card title="Icons">
    </x-renderer.card>
    <x-renderer.card title="Grids">
        Grid colSpan=6
        <x-renderer.grid colSpan="6" :items="$gridDataSource" itemRenderer="x-renderer.avatar-name"></x-renderer.grid>
        <br />
        Grid colSpan=4 groupBy=name 
        <x-renderer.grid colSpan="4" :items="$gridDataSource" itemRenderer="x-renderer.avatar-name" groupBy="name"></x-renderer.grid>
    </x-renderer.card>
    <x-renderer.card title="Tags">
        <x-renderer.table showNo=true :columns="$tagColumns" :dataSource="$tagDataSource" maxH=45/>

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
    
    
</div>