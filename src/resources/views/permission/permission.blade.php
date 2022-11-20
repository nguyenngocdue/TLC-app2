@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
<x-renderer.table showNo=true :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
<br />
<hr />
<x-form.create-new method="post" footer="Pipe is allowed. E.G.: name1|name2|name3|..." />
<br />
<br />
<br />
@endsection