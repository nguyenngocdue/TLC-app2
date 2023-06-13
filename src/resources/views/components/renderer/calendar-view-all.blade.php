<div class="w-full p-2 m-2 bg-gray-100 rounded-lg shadow">
    <div class = "flex flex-wrap justify-center">
        <div class="grid grid-cols-3 font-semibold" calendar-container>
        </div> 
    </div>
</div>
<script>
    calendarContainer = document.querySelector("[calendar-container]");
    const month_names = ['10','11','12','01','02','03','04','05','06','07','08','09','10','11','12'];
    const day_names = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];  
    let year = '';
    const allTimesheet = @json($allTimesheet);
    const routeCreate = @json($routeCreate);
    const token = @json($token);
    renderHtmlCalendar()
    function days_of_month(){
            all = (year) => {return (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) || (year % 100 === 0 && year % 400 ===0)};
            feb = (year) => {return all(year) ? 29 : 28};
            return [31, 30, 31 ,31, feb(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
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
            if(k <= 2){
                for (let i = 1; i <= dayOfMonth[k]; i++) {
                if(days[k].length < day_of_week(year -1,k + 9)) {
                    i-=i;
                    days[k].push('');
                    continue; 
                };
                days[k].push(i);
            } 
            }else{
                for (let i = 1; i <= dayOfMonth[k]; i++) {
                if(days[k].length < day_of_week(year,k - 3)) {
                    i-=i;
                    days[k].push('');
                    continue; 
                };
                days[k].push(i);
            }}
            
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
    
    function renderHtmlCalendar(){
        calendarContainer.innerHTML = '';
        yearNow();
        weeks = weeksGenerater();
        htmlRender = '';
        for (let i = 0; i < month_names.length; i++) {
            yearCurrent = (i <= 2) ? year - 1 : year;
            const month_name = month_names[i];
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
                    var lengthFirst = firstPart.length;
                    var lengthSecond = secondPart.length;
                    html_array_1 = '';
                    firstPart.forEach(day => {
                        html_array_1 += `<p>${day}</p>`
                    });
                    urlCreate = genUrlCreate(routeCreate,firstPart,yearCurrent,getIndexMonth(i));
                    [url,classHover,id,bg_color,text_color] = checkTimeSheet(allTimesheet,firstPart,getIndexMonth(i),yearCurrent,routeCreate);
                    firstHtml =  url
                    ? `<a href="${url}" title="#${id}" class="${classHover} col-span-${lengthFirst} focus:outline-none text-white bg-${bg_color} hover:bg-${text_color} focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <div class="grid grid-cols-${lengthFirst} gap-1 font-semibold text-center text-${text_color} hover:text-${bg_color}">
                                ${html_array_1}
                            </div>
                        </a>` : `<div title="Create new timesheet"  class="${classHover} col-span-${lengthFirst} focus:outline-none text-white bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <button  onclick="callApiCreateTimesheet('${urlCreate}')" class="w-full grid grid-cols-${lengthFirst} gap-1 font-semibold text-center text-gray-800">
                                    ${html_array_1}
                            </button>
                        </div>`;
                    html_array_2 = '';
                    secondPart.forEach(day => {
                        html_array_2 += `<p>${day}</p>`
                    });
                    urlCreate = genUrlCreate(routeCreate,secondPart,yearCurrent,getIndexMonth(i));
                    [url,classHover,id,bg_color,text_color] = checkTimeSheet(allTimesheet,secondPart,getIndexMonth(i),yearCurrent,routeCreate);
                    sencondHtml =  url
                    ? `<a href="${url}" title="#${id}" class="${classHover} col-span-${lengthSecond} focus:outline-none text-white bg-${bg_color} hover:bg-${text_color} focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <div class="grid grid-cols-${lengthSecond} gap-1 font-semibold text-center text-${text_color} hover:text-${bg_color}">
                                ${html_array_2}
                            </div>
                        </a>` : `<div title="Create new timesheet" class="${classHover} col-span-${lengthSecond} focus:outline-none text-white bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <button onclick="callApiCreateTimesheet('${urlCreate}')" class="w-full grid grid-cols-${lengthSecond} gap-1 font-semibold text-center text-gray-800">
                                    ${html_array_2}
                            </button>
                        </div>`;
                    html += `<div class="grid grid-cols-7 gap-0 font-semibold">
                                ${firstHtml}
                                ${sencondHtml}
                            </div>`
                }else{
                    dayHtml = '';
                    urlCreate = genUrlCreate(routeCreate,weeksDivide,yearCurrent,getIndexMonth(i));
                    [url,classHover,id,bg_color,text_color] = checkTimeSheet(allTimesheet,weeksDivide,getIndexMonth(i),yearCurrent,routeCreate);
                    weeksDivide.forEach(day => {
                        dayHtml += `<p>${day}</p>`
                    });
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
            htmlRender = `  <div class="p-1 m-1 font-sans bg-white rounded shadow-md w-96 bg-blend-luminosity bg-gradient-to-b from-green-50 via-white to-green-50">
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
    function getIndexMonth(i){
        return (i<= 2) ? (i+10):(i-2);
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
                }
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
                        if(sunday(day,month,yearCurrent)){
                            return [false,classHover,'','',''];
                        }
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