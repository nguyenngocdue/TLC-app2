<x-mail::message>
# Introduction
The body of your message.
<x-mail::button :url="$url">
    View Document
</x-mail::button>
@empty(!$dataNoOrFail)
<x-mail::table>
    |**Check Points**|  **Content**  | **Creator**  |
    | :------------- |:-------------:|:-------------|
    @foreach($dataNoOrFail as $key => $value)
        @php
            switch($value['qaqc_insp_control_value_id']) {
                case 2:
                    $conent = "No";
                    break;
                case 6:
                    $conent = "Fail";
                    break;
                default:
                    $conent = "Unknown";
                    break;
            }
        @endphp
    |{{$value['name']}}| {{$conent}}|{{$creator}}|
    @endforeach
</x-mail::table>
@endempty
@empty(!$dataComment)
<x-mail::table>
    |**Check Points** |  <center>Content</center>   | **Creator**    |
    | :-------------  |:--------------------------  |:-------------  |
    @foreach($dataComment as $key => $value)
    |{{$value['content_line_name']}}|{{$value['content']}}|{{$creator}}|
    @endforeach
</x-mail::table>
@endempty

Thank you for using our application!<br>
{{ config('app.name') }} Modular
</x-mail::message>
