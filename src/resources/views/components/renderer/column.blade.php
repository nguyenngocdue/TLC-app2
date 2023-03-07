@php
$result = [];
$json = json_decode($slot);
if(!is_array($json)) $json=[$json];
foreach($json as $item){
    $id = $item->id ?? "";
    $value = null;
    if(!isset($item->$rendererParam)) {
        // dump('l1'.$rendererParam);
        if($rendererParam !== 'name'){
            $result[] = $rendererParam." is missing";
        }else{
            $value = "Nameless #".($id);
        }
    }else {
        // dump('l2');
        $value = $item->$rendererParam;
    }
    $result [] = "<span title='#{$id}'>".$value."</span>";
}
echo "<p class='p-2'>" .join(", ", $result)."</p>";
@endphp

