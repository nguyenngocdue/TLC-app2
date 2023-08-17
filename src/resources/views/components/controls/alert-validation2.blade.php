@error($name)
@php $messages = $errors->default->getMessages()[$name]; @endphp
{{-- <strong class="text-red-400">{{$label}}:</strong> --}}
<span class="text-xs" role="alert">
    <ul class="mt-1.5 text-red-600 font-semibold">
        @foreach($messages as $message)
        <li>{{$name}}: {!!$message!!}</li>
        @endforeach
    </ul>
</span>
@enderror
