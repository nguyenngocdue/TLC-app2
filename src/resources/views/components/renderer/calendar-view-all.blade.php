<div class="w-full p-2 m-2 bg-gray-100 rounded-lg shadow">
    <div class = "flex flex-wrap justify-center">
        <div class="grid grid-cols-3 font-semibold" calendar-container>
        </div> 
    </div>
</div>
<script>
    calendarContainer = document.querySelector("[calendar-container]");
    const month_names = ['October','November','December','January','February','March','April','May','June','July','August','September','October','November','December'];
    const day_names = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];  
    let year = '';
    allTimesheet = @json($allTimesheet);
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
                    valueFirst = checkTimeSheet(allTimesheet,firstPart,getIndexMonth(i),yearCurrent);
                    firstHtml =  valueFirst
                    ? `<a href="${valueFirst}" class="col-span-${lengthFirst} focus:outline-none text-white bg-red-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <div class="grid grid-cols-${lengthFirst} gap-1 font-semibold text-center text-gray-800">
                                ${html_array_1}
                            </div>
                        </a>` : `<div class="col-span-${lengthFirst} focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <div class="grid grid-cols-${lengthFirst} gap-1 font-semibold text-center text-gray-800">
                                ${html_array_1}
                            </div>
                        </div>`;
                    html_array_2 = '';
                    secondPart.forEach(day => {
                        html_array_2 += `<p>${day}</p>`
                    });
                    valueSecond = checkTimeSheet(allTimesheet,secondPart,getIndexMonth(i),yearCurrent);
                    sencondHtml =  valueSecond
                    ? `<a href="${valueSecond}" class="col-span-${lengthSecond} focus:outline-none text-white bg-red-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <div class="grid grid-cols-${lengthSecond} gap-1 font-semibold text-center text-gray-800">
                                ${html_array_2}
                            </div>
                        </a>` : `<div class="col-span-${lengthSecond} focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <div class="grid grid-cols-${lengthSecond} gap-1 font-semibold text-center text-gray-800">
                                ${html_array_2}
                            </div>
                        </div>`;
                    html += `<div class="grid grid-cols-7 gap-0 font-semibold text-center text-gray-800">
                                ${firstHtml}
                                ${sencondHtml}
                            </div>`
                }else{
                    dayHtml = '';
                    valueCheck = checkTimeSheet(allTimesheet,weeksDivide,getIndexMonth(i),yearCurrent);
                    weeksDivide.forEach(day => {
                        dayHtml += `<p>${day}</p>`
                    });
                    html += valueCheck ? `<div class="grid grid-cols-7 gap-0 font-semibold text-center text-gray-800">
                                                <a href="${valueCheck}" class="col-span-7 focus:outline-none text-white bg-red-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 mr-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                    <div class="grid grid-cols-7 gap-1 font-semibold text-center text-gray-800">
                                                        ${dayHtml}
                                                    </div>
                                                </a>
                                            </div>` 
                                            : `<div class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm py-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            <div class="grid grid-cols-7 gap-1 font-semibold text-center text-gray-800">
                                                ${dayHtml}
                                            </div>
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
                                    ${result}
                                </div>
                            </div>`
            calendarContainer.innerHTML += htmlRender;
        }
        
    }
    function getIndexMonth(i){
        return (i<= 2) ? (i+10):(i-2);
    }
    function checkTimeSheet(allTimesheet,days,month,yearCurrent){
        if(allTimesheet[yearCurrent]){
            for (let timesheet of allTimesheet[yearCurrent]) {
                if(typeof days[0] == 'number'){
                    isTrue = getValueUrlByDay(timesheet,yearCurrent,month,days[0]);
                    if(isTrue){
                        console.log(isTrue);
                        return isTrue;
                    }
                }
                for (let day of days) {
                    if(typeof day == 'number' ){
                        return getValueUrlByDay(timesheet,yearCurrent,month,day);
                        if(isTrue){
                            return isTrue;
                        }
                    }
                }
            }
        }
        return false;
    }
    function getValueUrlByDay(timesheet,yearCurrent,month,day){
                week = moment(`${yearCurrent}-${month}-${day}`).isoWeek();
                if(week == timesheet.week_value){
                    
                    if(day == 26){
                        if(sunday(day,month,yearCurrent)) return timesheet.url;
                        return false;
                    }
                    return timesheet.url;
                }
                return 
    }
</script>