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
            $result[] = "Renderer View All Param [".$rendererParam."] is missing";
            continue;
        }else{
            $value = "";
            // $value = "Nameless #".($id); //<< This will cause eye noises
        }
    }else {
        $value = $item->$rendererParam;
    }
    $result [] = "<span title='#{$id}'>".$value."</span>";
}
echo "<p class='p-2'>" .join(", ", $result)."</p>";
@endphp

