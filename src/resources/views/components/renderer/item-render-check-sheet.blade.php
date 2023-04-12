@foreach($lines as $line)
    <x-controls.check-point :line="$line" />
@endforeach