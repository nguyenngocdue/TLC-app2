<x-renderer.grid 
    colSpan={{$colSpan}} 
    :items="$dataSource" 
    itemRenderer="x-renderer.avatar-name" 
    groupBy="name"></x-renderer.grid>
