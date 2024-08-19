<div class="grid grid-cols-12 gap-2">
    <div class="col-span-3 overflow-y-auto overflow-x-hidden h-screen1 px-2 bg-white rounded" style="height: 700px">
        <x-calendar.sidebar-calendar-view-all type="{{$type}}" typeModel="{{$typeModel}}"/>
    </div>
    <div class="w-full px-1 bg-gray-100 rounded-lg shadow col-span-9">
        <div class="grid grid-cols-12 gap-2 mx-2">
            <div class="col-span-12 md:col-span-8 xl:col-span-4">
                    <x-renderer.card title="Selected view">
                        <div class="flex">
                            <x-renderer.avatar-user uid='{{$userCurrentCalendar->id}}'></x-renderer.avatar-user>
                            <x-renderer.button 
                                class="w-40" 
                                icon="fa-duotone fa-briefcase" 
                                click="toggleModal('modal-task-list')"
                                keydownEscape="closeModal('modal-task-list')"
                                >
                                Task List
                            </x-renderer.button>
                            <x-modals.modal-task-list 
                                modalId='modal-task-list' 
                                nodeProjectTreeArray="{!! $nodeProjectTreeArray !!}" 
                                nodeTaskTreeArray="{!! $nodeTaskTreeArray !!}" 
                                selectedUser="{{$ownerId}}"
                                :userCurrentCalendar="$userCurrentCalendar"
                                />
                        </div>
                    </x-renderer.card>
            </div>
            <div class="col-span-12 md:col-span-4 xl:col-span-2">
                <x-renderer.card title="Selected Year">
                        <div class="text-center flex justify-around items-center">
                            <button type="button" onclick="decrease(1)" class="text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 dark:hover:bg-blue-500">
                                <i class="w-5 h-full fa-regular fa-arrow-left"></i>
                            </button>
                            <span id="current-year">{{$year}}</span>
                            <button type="button" onclick="increase(1)" class="text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 dark:hover:bg-blue-500">
                            <i class="w-5 h-full fa-regular fa-arrow-right"></i>
                            </button>
                        </div>
                </x-renderer.card>
            </div>
            <div class="col-span-12 md:col-span-8 xl:col-span-4">
                <x-renderer.legend type="{{$type}}" title="{{$titleLegend}}">
                    <hr/>
                    <div class="flex justify-evenly mt-1">
                        <div class="text-xs">
                            <span class="rounded-full font-bold px-4 bg-yellow-300 text-yellow-600 mr-1">.</span> Leave
                        </div>
                        <div class="text-xs">
                            <span class="rounded-full font-bold px-4 bg-red-300 text-red-600 mr-1">.</span> Public hodiday
                        </div>
                        <div class="text-xs">
                            <span class="rounded-full font-bold px-4 bg-gray-500 text-gray-100 mr-1">.</span> Today
                        </div>
                    </div>
                </x-renderer.legend>
            </div>
            <div class="col-span-12 md:col-span-4 xl:col-span-2">
            @php
                $listIdPendingApproval = json_encode($listIdPendingApproval);
            @endphp
                <x-renderer.card title="Options">
                    <x-renderer.button                         
                        icon="fa-duotone fa-thumbs-up"
                        class="bg-green-200 text-green-800 w-full"
                        onClick="changeStatusAll('{{$routeChangeStatusMultiple}}',{{$listIdPendingApproval}}, 'approved', 'Approved')"
                        disabled="{{$disableButton}}"
                        >
                        Approve All
                    </x-renderer.button>
                </x-renderer.card>
            </div>
            
        </div>
        <div class="mt-2 grid grid-cols-12 ">
            <div class="col-span-12 overflow-y-auto overflow-x-hidden h-screen1">
                <div class = "flex flex-wrap justify-center">
                    <div class="grid 2xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-2 font-semibold" calendar-container>
                    </div> 
                </div>
                <div class="p-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
    calendarContainer = document.querySelector("[calendar-container]");
    let count = 0;
    const leaveDates = @json($leaveDates);
    const publicHolidays = @json($publicHolidays);
    // const month_names = ['9','10','11','12','01','02','03','04','05','06','07','08','09','10','11','12'];
    const month_names = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    const day_names = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    let year = @json($year) * 1;  
    const allTimesheet = @json($allTimesheet);
    const routeCreate = @json($routeCreate);
    const token = @json($token);
    const typeEntity = @json($type);
    const ownerId = @json($ownerId);
    const useTsForPayroll = @json($useTsForPayroll);

</script>
<script src="{{ asset('js/components/FullCalendarViewAll-20240819.js') }}"></script>