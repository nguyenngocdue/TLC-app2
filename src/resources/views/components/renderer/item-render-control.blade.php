
@if (is_null($control))
<h2 class="text-red-400">{{"Control of this $columnName has not been set"}}</h2>
@endif

{{-- Invisible anchor for scrolling when users click on validation fail message --}}
<strong class="scroll-mt-20 snap-start" id="scroll-{{$columnName}}"></strong>
@switch ($control)

    @case('id')
    @case('doc_id')
    <x-controls.id2 name={{$columnName}} value="{{$action === 'edit' ? $value : 'to be generated'}}" />
    @break

    @case('hyperlink')
    @php $placeholder="https://www.google.com"; @endphp

    @case('text')
    @case('thumbnail')
    @case('parent_id')
    <x-controls.text2 name={{$columnName}} value={{$value}} placeholder="{{$placeholder}}" readOnly={{$readOnly}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('number')
    <x-controls.number2 name={{$columnName}} numericScale={{$numericScale}} value={{$value}} readOnly={{$readOnly}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('textarea')
    <x-controls.textarea2 name={{$columnName}} :value="$value" colType={{$columnType}} placeholder="{{$placeholder}}" readOnly={{$readOnly}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('textarea_diff')
    <x-controls.textarea-diff2 name={{$columnName}} :value="$value" :value2="$value2" colType={{$columnType}} placeholder="{{$placeholder}}" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break
    @case('textarea_diff_draft')
    <x-controls.textarea-diff2 mode="draft" name={{$columnName}} :value="$value" :value2="$value2" colType={{$columnType}} placeholder="{{$placeholder}}" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('toggle')
    <x-controls.toggle2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('signature')
    <x-controls.signature.signature2a name={{$columnName}} value={{$value}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('status')
        @if($status)
        <x-controls.status-dropdown2 value={{$status}} name={{$columnName}} modelPath={{$modelPath}} readOnly={{$readOnly}} />
        @endif
    @break

    @case ('dropdown')
    @php $value = $value ? $value : $defaultValue; @endphp
    <x-controls.has-data-source.dropdown2 action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case ('radio')
        <x-controls.has-data-source.radio-or-checkbox2 action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} />
        <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case ('dropdown_multi')
        @if($columnName == 'getRoleSet')
            {{(isset($item->getRoleSet) && isset($item->getRoleSet[0]) )? $item->getRoleSet[0]->name : ""}}
            <x-renderer.button href="/dashboard/admin/setrolesets/{{$id}}/edit" target="_blank">Edit RoleSet in popup</x-renderer.button>
        @else
            <x-controls.has-data-source.dropdown2 action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}} />
            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
        @endif
    @break

    @case('checkbox')
    <x-controls.has-data-source.radio-or-checkbox2 action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case ('dropdown_multi_2a')
        @if($columnName == 'getRoleSet')
            {{(isset($item->getRoleSet) && isset($item->getRoleSet[0]) )? $item->getRoleSet[0]->name : ""}}
            <x-renderer.button href="/dashboard/admin/setrolesets/{{$id}}/edit" target="_blank">Edit RoleSet in popup</x-renderer.button>
        @else
            <x-controls.has-data-source.dropdown2a action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}} />
            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
        @endif
    @break
   
    @case('checkbox_2a')
    <x-controls.has-data-source.radio-or-checkbox2a action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('picker_time')
    <x-controls.picker-time2 name={{$columnName}} component="controls/picker_time2" value={{$value}} readOnly={{$readOnly}} dateTimeType="{{$control}}" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break
    @case('picker_datetime')
    <x-controls.picker-datetime2 name={{$columnName}} component="controls/picker_datetime2" value={{$value}} readOnly={{$readOnly}} dateTimeType="{{$control}}" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('picker_date')
    <x-controls.picker-date2 name={{$columnName}} component="controls/picker_date2" value={{$value}} readOnly={{$readOnly}} dateTimeType="{{$control}}" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('picker_week')
    <x-controls.text2 name={{$columnName}} component="controls/picker_week" value={{$value}} readOnly={{$readOnly}} placeholder="W01/YYYY" icon="fa-solid fa-calendar-day" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('picker_month')
    <x-controls.text2 name={{$columnName}} component="controls/picker_month" value={{$value}} readOnly={{$readOnly}} placeholder="MM/YYYY" icon="fa-solid fa-calendar-day" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('picker_quarter')
    <x-controls.text2 name={{$columnName}} component="controls/picker_quarter" value={{$value}} readOnly={{$readOnly}} placeholder="Q1/YYYY" icon="fa-solid fa-calendar-day" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('picker_year')
    <x-controls.text2 name={{$columnName}} component="controls/picker_year" value={{$value}} readOnly={{$readOnly}} placeholder="YYYY" icon="fa-solid fa-calendar-day" />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('attachment')
    <x-renderer.attachment2 name={{$columnName}} :value="$valueArray" :properties="$prop['properties']" readOnly={{$readOnly}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('comment')
    <x-controls.comment.comment-group2a commentableId={{$id}} commentableType="{{$type}}" category="{{$columnName}}" readOnly={{$readOnly}} />
    <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
    @break

    @case('signature_multi')
        @if($action === "create")
            <div title="[{{$prop['label']}}] table will appear after this document is created">
                <i class="fa-duotone fa-square-question text-yellow-800"></i>
            </div>
        @else
            <x-controls.signature.signature-group2a :item="$item" signableId={{$id}} type="{{$type}}" category="{{$columnName}}" readOnly={{$readOnly}}  />
            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
        @endif
    @break

    @case('relationship_renderer')
        @if($action === "create")
            <div title="[{{$prop['label']}}] table will appear after this document is created">
                <i class="fa-duotone fa-square-question text-yellow-800"></i>
            </div>
        @else
            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
            <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} readOnly={{$readOnly}} :item="$item" />
        @endif
    @break

    @case('parent_type')
    <x-controls.parent_type2 type={{$type}} name={{$columnName}} selected="{{$value}}" readOnly={{$readOnly}} />
    @break

    @case('parent_link')
    <x-feedback.alert type="warning" title="Warning" message="{{$control}} suppose to show in View All screen only, please do not show in Edit screen." />
    @break

    @default
    <x-feedback.alert type="warning" title="Control" message="Unknown how to render control [{{$control}}/{{$columnName}}]" />
    @break

@endswitch

@php
    switch($controlExtra)
    {
        case '$link_to_user_position':
            $link = route("user_positions.show", $value);
            $controlExtra = "<a target='_blank' class='text-blue-800' href='$link'>Show this Job Description</a>";
            break;
    }
@endphp

<div component="control-extra" class="text-gray-600 text-sm">{!! $controlExtra !!}</div>
