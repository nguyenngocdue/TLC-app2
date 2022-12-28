@php
$result = [];
$json = json_decode($slot);
if(!is_array($json)) $json=[$json];
foreach($json as $item){
    if(!isset($item->$rendererParam)) break;
    $result [] = "<span title='#{$item->id}'>".$item->$rendererParam."</span>";
}
echo join(", ", $result);
@endphp