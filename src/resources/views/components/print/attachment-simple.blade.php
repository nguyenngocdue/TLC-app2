@foreach($dataSource as $value)
    <a href="{{$pathMinio.$value['url_media']}}" class="text-blue-500 text-base">
        <p>
            <i class="fa-light fa-file mr-1"></i>{{$value['filename']}}
        </p>
    </a>
@endforeach
