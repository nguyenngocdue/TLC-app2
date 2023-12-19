@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div id="divMain"></div>

<script src="{{ asset('js/editable-table.js') }}"></script>
<script>
    
const columns = DataSource.UserColumns
const dataSource = DataSource.Users

const tables = `
    Editable Mode
    ${EditableTable({columns, dataSource, settings: DataSource.TableSettings.editableMode})}
    Print Mode
    ${EditableTable({columns, dataSource, settings: DataSource.TableSettings.printMode})}
`

$("#divMain").html(tables)
</script>

@endsection 