@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<x-renderer.conqa_archive.conqa_archive :item="$item" />

@endsection