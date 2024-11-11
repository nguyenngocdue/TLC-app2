@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<x-renderer.table3
    :columns="$columns"
    :dataSource="$dataSource"
    
    showNo=1
    tableDebug="1"

    showPaginationTop={{true}}
    topLeftControl="Top left controls"
    topCenterControl="Top center controls"
    topRightControl="Top right controls"

    showPaginationBottom={{true}}
    bottomLeftControl="Bottom left controls"
    bottomCenterControl="Bottom center controls"
    bottomRightControl="Bottom right controls"

    header="Table Header"
    footer="Table Footer"

    ></x-renderer.table>

@endsection 