<ol class="flex justify-end">
    @for($i = 0; $i < sizeof($links)-1; $i++)
    <li><a class="text-blue-500 hover:text-gray-400" href="{{$links[$i]['href']}}">{{$links[$i]['title']}}</a></li> 
    <li><span class="text-gray-500 mx-1">/</span></li>
    @endfor
    <li><a class="text-blue-500 hover:text-gray-400" href="{{$links[sizeof($links)-1]['href']}}">{{$links[sizeof($links)-1]['title']}}</a></li> 
</ol>
