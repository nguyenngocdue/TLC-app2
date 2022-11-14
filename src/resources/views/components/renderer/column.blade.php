@php
$result = [];
$fault = false;
foreach(json_decode($slot) as $item){
if(!isset($item->$rendererParam)) {
$fault = true;
break;
}
$result [] = $item->$rendererParam;
}
echo join(", ", $result);
if($fault) echo "[$rendererParam] not found";
@endphp