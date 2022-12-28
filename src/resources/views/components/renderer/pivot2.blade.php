@php
    $json = json_decode($slot);
    // echo $value1." - " .$value2;
    if(is_array($json)){
        foreach($json as $line){
            if($line->$param1 == $value1 && $line->$param2 == $value2){
                echo $line->$column;
                // dump($line);
                break;
            }
        }
    } else {
        echo "?";
    }
    // dump($json);
@endphp

