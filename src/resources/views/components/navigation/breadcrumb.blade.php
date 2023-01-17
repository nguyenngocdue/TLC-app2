<ol class="flex justify-end">
    @for($i = 0; $i < sizeof($linkCrud)-1; $i++)
    <li><a class="text-blue-500 hover:text-gray-400" href="{{$linkCrud[$i]['href']}}">{{$linkCrud[$i]['title']}}</a></li> 
    <li><span class="text-gray-500 mx-1">/</span></li>
    @endfor
    <li><a class="text-blue-500 hover:text-gray-400" href="{{$linkCrud[sizeof($linkCrud)-1]['href']}}">{{$linkCrud[sizeof($linkCrud)-1]['title']}}</a></li> 
</ol>
<ol class="flex justify-end">
    @for($i = 0; $i < sizeof($linkManageJson)-1; $i++)
    <li><a class="text-blue-500 hover:text-gray-400" href="{{$linkManageJson[$i]['href']}}">{{$linkManageJson[$i]['title']}}</a></li> 
    <li><span class="text-gray-500 mx-1">/</span></li>
    @endfor
    <li><a class="text-blue-500 hover:text-gray-400" href="{{$linkManageJson[sizeof($linkManageJson)-1]['href']}}">{{$linkManageJson[sizeof($linkManageJson)-1]['title']}}</a></li> 
</ol>