@php
$result = [];
$json = json_decode($slot);
if(!is_array($json)) $json=[$json];
foreach($json as $item){
    if(!isset($item->$rendererParam)) {
        // dump('l1'.$rendererParam);
        if($rendererParam !== 'name'){
            $result[] = $rendererParam." is missing";
        }else{
            $value = "Nameless #".$item->id;
        }
    }else {
        // dump('l2');
        $value = $item->$rendererParam;
    }
    $result [] = "<span title='#{$item->id}'>".$value."</span>";
}
echo join(", ", $result);
@endphp

