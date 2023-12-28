
<div id='calendar-holiday'></div>
<script type="text/javascript">
    const calendarEl = document.getElementById('calendar-holiday');
    let calendar = null;
    let currentYear = @json($currentYear);
    let initialView = "dayGridMonth";
    const apiUrl = @json($apiUrl);
    const token = @json($token);
    modalContainer = document.querySelector("[modal-container]");
    let events = [];
    callApiGetEvents(apiUrl,currentYear,initialView);
    function callApiGetEvents(url,currentYear,initialView) {
        $.ajax({
            type: 'get',
            url: `${url}`,
            // data: {
            //     "year": currentYear,
            // },
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                if (response) {
                    events = response.hits.data
                    eventsMap = {};
                    for( event of events){
                        if(eventsMap[event.start]){
                            var oldVal = eventsMap[event.start].workplace_ids;
                            var newVal = event.workplace_ids;
                            if(newVal.length > oldVal.length){
                                eventsMap[event.start] = event;
                            }
                        }else 
                            eventsMap[event.start] = event;
                    }
                    var Calendar = FullCalendar.Calendar;
                    calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'multiMonthYear,dayGridMonth'
                        },
                        timeZone: 'local',
                        height: 850,
                        contentHeight: 830,
                        aspectRatio: 2,
                        // initialDate: response.meta,
                        initialView: initialView,
                        // multiMonthMaxColumns: 1,
                        locale: 'en',
                        dayMaxEvents: true,
                        weekNumbers: true,
                        editable: false,
                        droppable: false,
                        events: events,
                        eventContent: function(info) {
                            var timeText = info.timeText;
                            var eventTitle = info.event.title;
                            return {
                                html: eventTitle
                            };
                        },
                        dayCellDidMount: function(info){
                            if (info.view.type === 'multiMonthYear') {
                                var date = info.el?.dataset?.date
                                if(eventsMap[date]){
                                    info.el.style.backgroundColor = eventsMap[date].color; 
                                }
                            }
                        },
                        // datesSet: function(info){
                        //     var typeView = info.view.type;
                        //     var currentViewYear = info.view.currentStart.getFullYear();
                        //     if(currentViewYear !== currentYear){
                        //         currentYear = currentViewYear;
                        //         callApiGetEvents(url,currentYear,typeView);
                        //     }
                        // },
                        moreLinkContent:function(info){
                            return '...';
                        },
                    })
                    calendar.render();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {},
        })
    }
    
</script>