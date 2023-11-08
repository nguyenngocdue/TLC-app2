<x-renderer.table :columns="$columns" :dataSource="$dataSource" 
                    :rotate45Width="$rotate45Width" :rotate45Height="$rotate45Height"
                    showNo=1  maxH=0 />

<x-renderer.legend type="{{$type}}" title="Legend of Status" />
<br/>