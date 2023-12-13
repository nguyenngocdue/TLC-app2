@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@php $groupName = ''; @endphp

<form id="frmExam" action="{{$route}}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="exam_tmpl_id" value="{{$exam_tmpl_id}}" />
    <input type="hidden" name="exam_sheet_id" value="{{$exam_sheet_id}}" />
    <input type="hidden" id="txtStatus" name="status" value="finished" />
    <div class="grid grid-cols-12 gap-2 p-4 w-full">
        <div class="col-span-12 justify-center">
            <div class="flex w-full justify-center">
                <div class="w-1/3">
                    <p>Do you have any comments about the recent survey:</p>
                    <p>Bạn có bình luận gì về khảo sát vừa rồi:</p>
                    <textarea name="comment" class="w-full rounded p-2" rows="6"></textarea>
                    <x-renderer.button type='success' htmlType="submit" icon='fa-solid fa-paper-plane'>Send and Close</x-renderer.button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
   
</script>

@endsection 