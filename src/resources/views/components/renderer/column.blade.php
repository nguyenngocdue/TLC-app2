@php
$result = [];
//$fault = false;
$json = json_decode($slot);
if(!is_array($json)) $json=[$json];
foreach($json as $item){
if(!isset($item->$rendererParam)) {
//$fault = true;
break;
}
$result [] = $item->$rendererParam;
}
echo join(", ", $result);
//if($fault) echo "[$rendererParam] not found";
@endphp