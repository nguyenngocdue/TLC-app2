<x-renderer.table   :columns="$columns" 
                    :dataSource="$dataSource" 
                    :dataHeader="$dataHeader"
                    :rotate45Width="$rotate45Width" :rotate45Height="$rotate45Height"
                    showNo=1  maxH=0 lineIgnoreNo=1
                    />

<x-renderer.legend type="{{$type}}" title="Legend of Status" />
<br/>