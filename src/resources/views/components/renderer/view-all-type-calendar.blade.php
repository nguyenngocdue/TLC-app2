<div class="grid grid-cols-12">
    <div class="col-span-3 overflow-y-auto overflow-x-hidden h-screen px-2">
        <x-calendar.sidebar-calendar-view-all type="{{$type}}" typeModel="{{$typeModel}}"/>
    </div>
    <div class="w-full p-2 m-2 bg-gray-100 rounded-lg shadow col-span-9">
        <div class="grid grid-cols-12">
            <div class="col-span-11 overflow-y-auto overflow-x-hidden h-screen">
                <div class = "flex flex-wrap justify-center">
                    <div class="grid 2xl:grid-cols-3 xl:grid-cols-3 md:grid-cols-2 gap-2 grid-cols-1 font-semibold" calendar-container>
                    </div> 
                </div>
            </div>
            <div class="col-span-1">
                <div class="grid grid-cols-12 h-full">
                    <div class="col-span-6">

                    </div>
                    <div class="flex-col justify-between">
                        <button type="button" onclick="decrease(1)" class="text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 dark:hover:bg-blue-500">
                            <i class="w-5 h-full fa-regular fa-arrow-up"></i>
                        </button>
                        <button type="button" onclick="increase(1)" class="text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 dark:hover:bg-blue-500">
                            <i class="w-5 h-full fa-regular fa-arrow-down"></i>
                        </button>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>
<script>
    calendarContainer = document.querySelector("[calendar-container]");
    // const month_names = ['9','10','11','12','01','02','03','04','05','06','07','08','09','10','11','12'];
    const month_names = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    const day_names = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    let year = @json($year) * 1;  
    const allTimesheet = @json($allTimesheet);
    const routeCreate = @json($routeCreate);
    const token = @json($token);
    const typeEntity = @json($type);
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
            titleDayHtml = ''
            day_names.forEach(day => {
                var color ='';
                if(day == 'Sun')
                {
                    color = 'text-red-600'
                }
                classCss = `grid place-items-center ${color}`
                titleDayHtml += `<div class="${classCss}">
                                    <p >${day}</p>
                                </div>`
            });
            result = '';
            for (let j = 0; j < weeks[i].length; j++) {
                const weeksDivide = weeks[i][j];
                html = '';
                if(weeksDivide.includes(25) && (weeksDivide.indexOf(25) < weeksDivide.length - 1) ){
                    let index = weeksDivide.indexOf(25);
                    let firstPart = weeksDivide.slice(0, index + 1);
                    let secondPart = weeksDivide.slice(index + 1);
                    firstHtml = renderHtmlDaysForArrayDivide(firstPart,month,yearCurrent,routeCreate,allTimesheet);
                    sencondHtml = renderHtmlDaysForArrayDivide(secondPart,month,yearCurrent,routeCreate,allTimesheet);
                    html += `<div class="grid grid-cols-7 gap-0 font-semibold">
                                ${firstHtml}
                                ${sencondHtml}
                            </div>`
                }else{
                    dayHtml = '';
                    urlCreate = genUrlCreate(routeCreate,weeksDivide,yearCurrent,getIndexMonth(i));
                    [url,classHover,id,bg_color,text_color] = checkTimeSheet(allTimesheet,weeksDivide,getIndexMonth(i),yearCurrent,routeCreate);
                    dayHtml = renderHtmlDays(dayHtml,weeksDivide,getIndexMonth(i));
                    html += url ? `<div class="grid grid-cols-7 gap-0 font-semibold">
                                                <a href="${url}" title="#${id}" class="${classHover} col-span-7 focus:outline-none bg-${bg_color} hover:bg-${text_color} text-${text_color} hover:text-${bg_color} focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                    <div class="grid grid-cols-7 gap-1 font-semibold text-center ">
                                                        ${dayHtml}
                                                    </div>
                                                </a>
                                            </div>` 
                                            : `<div title="Create new timesheet"  class="focus:outline-none text-white bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                    <button onclick="callApiCreateTimesheet('${urlCreate}')" class="${classHover} w-full grid grid-cols-7 gap-1 font-semibold text-center text-gray-800">
                                                            ${dayHtml}
                                                    </button>
                                            </div>`
                }
                result += html
            }
            var tagMonthNow = monthNow(month) ? 'id="scroll-to-month"' : "";
            var offset = monthNow(month) ? 'scroll-to-month' : "";
            htmlRender = `  <div ${tagMonthNow} class="${offset} p-1 m-1 font-sans bg-white rounded shadow-md w-96 bg-blend-luminosity bg-gradient-to-b from-green-50 via-white to-green-50">
                                <p class="p-1 text-xl font-semibold text-center text-gray-800">${month_name}/${yearCurrent}</p>
                                <div class="p-1 m-1">
                                    <div class="grid grid-cols-7 font-semibold text-gray-500 border-b-2">
                                    ${titleDayHtml}
                                    </div>
                                    <div class='mt-2'>
                                        ${result}
                                    </div>
                                </div>
                            </div>`
            calendarContainer.innerHTML += htmlRender;
        }
        
    }
    function renderHtmlDaysForArrayDivide(array,month,year,routeCreate,allTimesheet) {
        const lengthArr = array.length;
        let htmlDays = '';
        htmlDays = renderHtmlDays(htmlDays,array,month);
        let urlCreate = genUrlCreate(routeCreate,array,year,month);
        [url,classHover,id,bg_color,text_color] = checkTimeSheet(allTimesheet,array,month,year,routeCreate);
        let result =  url
                    ? `<a href="${url}" title="#${id}" class="${classHover} col-span-${lengthArr} focus:outline-none text-white bg-${bg_color} hover:bg-${text_color} focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <div class="grid grid-cols-${lengthArr} gap-1 font-semibold text-center text-${text_color} hover:text-${bg_color}">
                                ${htmlDays}
                            </div>
                        </a>` : `<div title="Create new timesheet"  class="${classHover} col-span-${lengthArr} focus:outline-none text-white bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <button  onclick="callApiCreateTimesheet('${urlCreate}')" class="w-full grid grid-cols-${lengthArr} gap-1 font-semibold text-center text-gray-800">
                                    ${htmlDays}
                            </button>
                        </div>`;
        return result;
    }
    function renderHtmlDays(html,array,month){
        array.forEach(day => {
            isToday = dayNow(day,month);
            html += isToday ? `<p class='text-red-600 rounded-full h-5 bg-rose-300 items-center justify-center'>${day}</p>` : `<p>${day}</p>`;
        });
        return html;
    }
    function getIndexMonth(i){
        // return (i<= 3) ? (i+9):(i-3);
        return i + 1;
    }
    function genUrlCreate(routeCreate,weeksDivide,yearCurrent,month){
        if(typeof weeksDivide[0] == 'number'){
            month = padNumber(month);
            day = padNumber(weeksDivide[0]);
            urlCreate = `${routeCreate}?week=${yearCurrent}-${month}-${day}`
        }
        else{
            for (let day of weeksDivide) {
                if(typeof day == 'number'){
                    dayIsoWeek = moment(`${yearCurrent}-${month}-${day}`).startOf('isoWeek').format('YYYY-MM-DD');
                    urlCreate = `${routeCreate}?week=${dayIsoWeek}`;
                }
            }
        }
        return urlCreate;
    }
    function checkTimeSheet(allTimesheet,days,month,yearCurrent,routeCreate){
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
                if(week == timesheet.week_value){
                    classHover = `hover-${week}-${yearCurrent}`;
                    if(day == 26){
                        if(dateTime == timesheet.week){
                            return [timesheet.url,classHover,timesheet.id,timesheet.bg_color,timesheet.text_color];
                        }else{
                            return null;
                        }
                    }
                    return [timesheet.url,classHover,timesheet.id,timesheet.bg_color,timesheet.text_color];
                }
    }
    function callApiCreateTimesheet(url){
        $.ajax({
            type: 'get',
            url: url,
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if(response.success){
                    toastr.success(response.message);
                    window.location.replace(response.hits);
                }
                else{
                    toastr.warning(response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR.responseJSON.message);
            },
        })
    }
</script>