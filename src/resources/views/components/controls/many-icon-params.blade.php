<x-renderer.grid 
    colSpan={{$colSpan}} 
    :items="$dataSource" 
    itemRenderer="x-renderer.avatar-user" 
    groupBy="name"></x-renderer.grid>
