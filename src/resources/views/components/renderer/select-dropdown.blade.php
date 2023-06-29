<div id="{{$id}}" name="{{$name}}" class="bg-gray-50 z-10 hidden divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="{{$id}}Button">
        @foreach($dataSource as $name => $lines)
            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-gray-400"></div>
                <span class="flex-shrink mx-4 text-gray-400 uppercase">{{$name}}</span>
                <div class="flex-grow border-t border-gray-400"></div>
            </div>
            @foreach( $lines as $line)
                <li title="{{$line['tooltip'] ?? ''}}">
                    <a href="{{$line['href']}}" class="text-left block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{$line['title']}}</a>
                </li>
            @endforeach
        @endforeach
    </ul>
</div>