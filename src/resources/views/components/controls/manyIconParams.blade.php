<x-renderer.grid 
    colSpan="3" 
    :items="$dataSource" 
    itemRenderer="x-renderer.avatar-name" 
    groupBy="name"
></x-renderer.grid>