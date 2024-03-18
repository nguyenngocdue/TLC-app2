<div class="grid grid-cols-12 gap-2">
    <div class="col-span-3 overflow-y-auto overflow-x-hidden h-screen px-2 bg-white rounded">
        <x-calendar.sidebar-calendar-view-all type="{{$type}}" typeModel="{{$typeModel}}"/>
    </div>
    <div class="w-full px-1 bg-gray-100 rounded-lg shadow col-span-9">
        <div class="grid lg:grid-cols-12 gap-2 mx-2">
            <div class="md:col-span-3 flex">
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
            <div class="md:col-span-3 lg:col-span-2">
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
            <div class="md:col-span-3 lg:col-span-5">
                <x-renderer.legend type="{{$type}}" title="{{$titleLegend}}" />
            </div>
            <div class="md:col-span-3 lg:col-span-2">
            @php
                $listIdPendingApproval = json_encode($listIdPendingApproval);
            @endphp
                <x-renderer.card title="Options">
                    <x-renderer.button 
                        class="w-40" 
                        icon="fa-duotone fa-thumbs-up"
                        class="bg-green-200 text-green-800"
                        onClick="changeStatusAll('{{$routeChangeStatusMultiple}}',{{$listIdPendingApproval}}, 'approved', 'Approved')"
                        disabled="{{$disableButton}}"
                        >
                        Approve All
                    </x-renderer.button>
                </x-renderer.card>
            </div>
            
        </div>
        <div class="mt-2 grid grid-cols-12 ">
            <div class="col-span-12 overflow-y-auto overflow-x-hidden h-screen">
                <div class = "flex flex-wrap justify-center">
                    <div class="grid 2xl:grid-cols-3 lg:grid-cols-2 gap-2 grid-cols-1 font-semibold" calendar-container>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    calendarContainer = document.querySelector("[calendar-container]");
    let count = 0;
    // const month_names = ['9','10','11','12','01','02','03','04','05','06','07','08','09','10','11','12'];
    const month_names = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    const day_names = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    let year = @json($year) * 1;  
    const allTimesheet = @json($allTimesheet);
    const routeCreate = @json($routeCreate);
    const token = @json($token);
    const typeEntity = @json($type);
    const ownerId = @json($ownerId);
    if(!year){
        yearNow();
    }
    renderHtmlCalendar();
    function increase(num){
        year += num;
        window.location.replace(`?view_type=calendar&action=updateViewAllCalendar&_entity=${typeEntity}&year=${year}`);
    }
    function decrease(num){
        year -= num;
        window.location.replace(`?view_type=calendar&action=updateViewAllCalendar&_entity=${typeEntity}&year=${year}`);
    }
    function days_of_month(){
            all = (year) => {return (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) || (year % 100 === 0 && year % 400 ===0)};
            feb = (year) => {return all(year) ? 29 : 28};
            // return [30, 31, 30, 31 ,31, feb(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            return [31, feb(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    }
    function day_of_week(year,month) { 
        let day =  new Date(year,month).getDay();
        switch (day) {
            case 0:
            day = 6;
            break;
            case 1:
            day = 0;
            break;
            case 2:
            day = 1;
            break;
            case 3:
            day = 2;
            break;
            case 4:
            day = 3;
            break;
            case 5:
            day = 4;
            break;
            case 6:
            day = 5;
            break;
            default:
            day =  new Date(year,month).getDay()
            break;
            }
            return day;
    }
    function daysGenerater() {
        let days = [];
        const dayOfMonth = days_of_month();
        for (let k = 0; k < dayOfMonth.length; k++) {
            days.push([]);
            for (let i = 1; i <= dayOfMonth[k]; i++) {
                if(days[k].length < day_of_week(year,k)) {
                    i-=i;
                    days[k].push('');
                    continue; 
                };
            days[k].push(i);
            }
        }
        return days;
    }
    function weeksGenerater(){
        let weeks = [];
        const chunkSize = 7;
        const daysGenerater = this.daysGenerater();
        for (let k = 0; k < daysGenerater.length; k++) {
        arr = [];
        for (let i = 0; i <= daysGenerater[k].length; i++) {
            const chunk = daysGenerater[k].slice(i, i + chunkSize);
            i +=chunkSize -1;
            if(chunk.length > 0){
                arr.push(chunk);
            }
        }
            weeks.push(arr);
        }
        return weeks;
    }
    function yearNow() {
        let today = new Date();
        year = today.getFullYear();
        document.getElementById('current-year').innerText = year
    }
    function sunday(day,month,year) {
        return moment(`${year}-${month}-${day}`).isoWeekday() === 7;
    }
    function dayNow(day, month) {
        var today = moment().format('YYYY-MM-DD');
        var specificDate = `${year}-${month}-${day}`; 
        return moment(today).isSame(specificDate)
    }
    function monthNow(month){
        month = padNumber(month);
        var monthCurrent = moment().format('YYYY-MM'); 
        var dateTimeCurrent = `${year}-${month}`;
        return moment(monthCurrent).isSame(dateTimeCurrent);
    }
    function renderHtmlCalendar(){
        calendarContainer.innerHTML = '';
        weeks = weeksGenerater();
        htmlRender = '';
        for (let i = 0; i < month_names.length; i++) {
            // yearCurrent = (i <= 3) ? year - 1 : year;
            yearCurrent = year;
            const month_name = month_names[i];
            month = getIndexMonth(i);
            htmlWeeksTitle = ''
            day_names.forEach(day => {
                var color = day == 'Sun' ? 'text-red-600' : ''
                htmlWeeksTitle += `<div class="grid place-items-center ${color}">
                                        <p>${day}</p>
                                    </div>`
            });
            htmlWeeksContent = '';
            for (let j = 0; j < weeks[i].length; j++) {
                const valueWeek = weeks[i][j];
                htmlWeekContentAll = '';
                if(valueWeek.includes(25) && (valueWeek.indexOf(25) < valueWeek.length - 1) ){
                    let index = valueWeek.indexOf(25);
                    let valueWeek_1 = valueWeek.slice(0, index + 1);
                    let valueWeek_2 = valueWeek.slice(index + 1);
                    htmlWeekContent_1 = renderHtmlContentWeekForArrayDivide(valueWeek_1,month,yearCurrent,routeCreate,allTimesheet,true);
                    htmlWeekContent_2 = renderHtmlContentWeekForArrayDivide(valueWeek_2,month,yearCurrent,routeCreate,allTimesheet);
                    htmlWeekContentAll += `<div class="grid grid-cols-7 gap-0 font-semibold">
                                                ${htmlWeekContent_1}
                                                ${htmlWeekContent_2}
                                            </div>`
                }else{
                    htmlContentWeek = '';
                    var dataCreate = genDataCreate(valueWeek,yearCurrent,getIndexMonth(i));
                    [url,classHover,id,bg_color,text_color,count_duplicate] = transformDataByTimeSheet(allTimesheet,valueWeek,getIndexMonth(i),yearCurrent,routeCreate);
                    htmlContentWeek = renderHtmlContentWeek(htmlContentWeek,valueWeek,getIndexMonth(i));
                    var htmlCountDuplicate = count_duplicate > 1 ? `<x-renderer.badge>${count_duplicate}</x-renderer.badge>` : '';
                    htmlWeekContentAll += url ? `<div class="grid grid-cols-7 gap-0 font-semibold">
                                                    <a href="${url}" title="#${id}" onmouseover="onHover('${classHover}','${bg_color}','${text_color}')" class="relative ${classHover} col-span-7 focus:outline-none bg-${bg_color} hover:bg-${text_color} text-${text_color} hover:text-${bg_color} focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                        <div class="grid grid-cols-7 gap-1 font-semibold text-center ">
                                                            ${htmlContentWeek}
                                                        </div>
                                                        ${htmlCountDuplicate}
                                                    </a>
                                                </div>` 
                                            : `<div title="Create new timesheet" onmouseover="onHover('${classHover}','${bg_color}','${text_color}')"  class="focus:outline-none text-white bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                    <a href="javascript:callApiStoreEmpty('${routeCreate}',[{week: '${dataCreate}',owner_id:'${ownerId}'}], {caller: 'view-all-calendar'})" onclick="$(this).addClass('disabled');" class="${classHover} w-full grid grid-cols-7 gap-1 font-semibold text-center text-gray-800">
                                                            ${htmlContentWeek}
                                                    </a>
                                                </div>`
                }
                htmlWeeksContent += htmlWeekContentAll
            }
            var tagMonthNow = monthNow(month) ? 'id="scroll-to-month"' : "";
            var offset = monthNow(month) ? 'scroll-to-month' : "";
            htmlRender = `  <div ${tagMonthNow} class="${offset} p-1 m-1 font-sans bg-white rounded shadow-md md:w-96 w-80 bg-blend-luminosity bg-gradient-to-b from-green-50 via-white to-green-50">
                                <p class="p-1 text-xl font-semibold text-center text-gray-800">${month_name}/${yearCurrent}</p>
                                <div class="p-1 m-1">
                                    <div class="grid grid-cols-7 font-semibold text-gray-500 border-b-2">
                                        ${htmlWeeksTitle}
                                    </div>
                                    <div class='mt-2'>
                                        ${htmlWeeksContent}
                                    </div>
                                </div>
                            </div>`
            calendarContainer.innerHTML += htmlRender;
        }
        
    }
    function renderHtmlContentWeekForArrayDivide(array,month,year,routeCreate,allTimesheet,marginLeft = false) {
        const lengthArr = array.length;
        let htmlDays = '';
        htmlDays = renderHtmlContentWeek(htmlDays,array,month);
        var dataCreate = genDataCreate(array,year,month);
        [url,classHover,id,bg_color,text_color,count_duplicate] = transformDataByTimeSheet(allTimesheet,array,month,year,routeCreate);
        var htmlCountDuplicate = count_duplicate > 1 ? `<x-renderer.badge>${count_duplicate}</x-renderer.badge>` : '';
        margin = marginLeft ? 'mr-1' : '';
        let result =  url
                    ? `<a href="${url}" title="#${id}" onmouseover="onHover('${classHover}','${bg_color}','${text_color}')" class="relative ${classHover} col-span-${lengthArr} focus:outline-none text-${text_color} hover:text-${bg_color} bg-${bg_color} hover:bg-${text_color} focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 ${margin} dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <div class="grid grid-cols-${lengthArr} gap-1 font-semibold text-center">
                                ${htmlDays}
                            </div>
                            ${htmlCountDuplicate}
                        </a>
                        ` : `<div title="Create new timesheet" onmouseover="onHover('${classHover}','${bg_color}','${text_color}')"  class="${classHover} col-span-${lengthArr} focus:outline-none text-white bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 ${margin} dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <a href="javascript:callApiStoreEmpty('${routeCreate}',[{week: '${dataCreate}',owner_id:'${ownerId}'}], {caller: 'view-all-calendar'})"  onclick="$(this).addClass('disabled');" class="w-full grid grid-cols-${lengthArr} gap-1 font-semibold text-center text-gray-800">
                                        ${htmlDays}
                                </a>
                            </div>`;
        return result;
    }
    function renderHtmlContentWeek(html,array,month){
        array.forEach(day => {
            isToday = dayNow(day,month);
            html += isToday 
            ? `<p class='text-red-600 rounded-full h-5 bg-rose-300 items-center justify-center'>
                ${day}
                </p>` 
            : `<p>
                ${day}
                </p>`;
        });
        return html;
    }
    function getIndexMonth(i){
        // return (i<= 3) ? (i+9):(i-3);
        return i + 1;
    }
    function genDataCreate(valueWeek,yearCurrent,month){
        if(typeof valueWeek[0] == 'number'){
            month = padNumber(month);
            day = padNumber(valueWeek[0]);
            data = `${yearCurrent}-${month}-${day}`;
        }
        else{
            for (let day of valueWeek) {
                if(typeof day == 'number'){
                    dayIsoWeek = moment(`${yearCurrent}-${month}-${day}`).startOf('isoWeek').format('YYYY-MM-DD');
                    data = dayIsoWeek;
                }
            }
        }
        return data;
    }
    function transformDataByTimeSheet(allTimesheet,days,month,yearCurrent,routeCreate){
        if(allTimesheet[yearCurrent]){
            for (let timesheet of allTimesheet[yearCurrent]) {
                if(typeof days[0] == 'number'){
                    value = getValueUrlByDay(timesheet,yearCurrent,month,days[0],routeCreate);
                    if(value){
                        if(value[0] || value[0] === false){
                                return value;
                            }
                    }
                }else{
                    for (let day of days) {
                        if(typeof day == 'number' ){
                            value = getValueUrlByDay(timesheet,yearCurrent,month,day,routeCreate);
                            if(value){
                                if(value[0] || value[0] === false){
                                    return value;
                                }
                            }
                        }
                    }
                }
                
            }
        }
        return [false,'','','',''];
    }
    function padNumber(number) {
        return number.toString().padStart(2, '0');
    }
    function getValueUrlByDay(timesheet,yearCurrent,month,day){
                month = padNumber(month);
                day = padNumber(day);
                dateTime = `${yearCurrent}-${month}-${day}`;
                week = moment(dateTime).isoWeek();
                startOfWeek = moment(dateTime).isoWeekday(1).format('YYYY-MM-DD');
                if(week == timesheet.week_value){
                    classHover = `hover-${week}-${yearCurrent}`;
                    if(day == 26){
                        if(dateTime == timesheet.week){
                            return [timesheet.url,classHover,timesheet.id,timesheet.bg_color,timesheet.text_color,timesheet.count_duplicate];
                        }
                        return null;
                    }else{
                        if(timesheet.week == startOfWeek){
                                return [timesheet.url,classHover,timesheet.id,timesheet.bg_color,timesheet.text_color,timesheet.count_duplicate];
                        }
                        return null;
                    }
                }
    }
    
    function onHover(classHover,bgColor,textColor){
        if(classHover){
        $(`.${classHover}`).hover(function() {
            $(`.${classHover}`).addClass(`bg-${textColor} text-${bgColor}`);
            }, function() {
            $(`.${classHover}`).removeClass(`bg-${textColor} text-${bgColor}`);
            });
        }
    }
</script>