<x-renderer.table   :columns="$columns" 
                    :dataSource="$dataSource" 
                    :dataHeader="$dataHeader"
                    :rotate45Width="$rotate45Width" 
                    :rotate45Height="$rotate45Height"
                    showPaginationTop="true"
                    
                    topLeftControl="{!! $actionButtons !!}"
                    topCenterControl=""
                    topRightControl=""
                    tableTrueWidth=1

                    headerTop={{$headerTop}}
                    maxH={{$maxH}}

                    showNo=1 lineIgnoreNo=1
                    />

<x-renderer.legend type="{{$type}}" title="Legend of Status" />
<br/>