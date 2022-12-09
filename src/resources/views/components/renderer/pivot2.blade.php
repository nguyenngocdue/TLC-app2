@php
    $json = json_decode($slot);
    foreach($json as $line){
        if($line->$param1 == $value1 && $line->$param2 == $value2){
            echo $line->$column;
            break;
        }
    }
@endphp