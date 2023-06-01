<div id='calendar'></div>
<div id="{{$modalId}}" class="hidden fixed flex z-10 items-center justify-center">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" onclick="closeModalEvent()">
                <!-- Modal header -->
                <div class="items-center justify-between p-2 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-lg justify-center font-semibold text-gray-900 dark:text-white">
                            Task: 
                        </h3>
                        <button type="button" onclick="closeModalEvent()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                            <i class="fa-sharp fa-solid fa-xmark w-6 h-6 text-base"></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <!-- Modal body -->
                <div class="px-6 overflow-y-auto w-96 h-96" data-container>
                    
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                    {{-- <x-renderer.button class="mx-2" type='success'>OK</x-renderer.button> --}}
                    <x-renderer.button click="onclick='closeModalEvent()'">Cancel</x-renderer.button>
                </div> 
            </div>
</div>
<script >
    const modalId = @json($modalId);
    const modal = $(`#`+modalId);
    let events = [];
    function callApiGetEvents(){
            $.ajax({
            type: 'get',
            url: 'https://dev2.tlcmodular.com/api/v1/hr/timesheet_staff/1',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            
            success: function (response) {
                if(response){
                    events = response.data
                }
                
            },
            error: function (jqXHR, textStatus, errorThrown) {},
        })
    }
    function closeModalEvent(){
        modal.addClass('hidden');
    }
    document.addEventListener('DOMContentLoaded', function() {
        const Calendar = FullCalendar.Calendar;
        const Draggable = FullCalendar.Interaction.Draggable;
        const containerEl = document.getElementById('task_id');
        const calendarEl = document.getElementById('calendar');
        var checkbox = document.getElementById('drop-remove');

        new Draggable(containerEl, {
        itemSelector: '.fc-event',
        eventData: function(eventEl) {
                return {
                    title: eventEl.innerText
                };
            }
        });
        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth,multiMonthYear,listWeek'
            },
            initialView: 'timeGridWeek',
            editable: true,
            droppable: true,
            events: events,
            eventClick: function(event){
                console.log(event);
                const {clientX , clientY} = event.jsEvent; 
                const modalWidth = modal.outerWidth();
                const modalHeight = modal.outerHeight();
                
                const modalTop = clientY + 10;
                const modalLeft = clientX - modalWidth / 2;
                modal.css({ top: modalTop, left: modalLeft }).removeClass('hidden');
            },
            drop: function(event) {
                
            }
        });
        calendar.render();
        });
        
</script>