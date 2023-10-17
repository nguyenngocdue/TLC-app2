<div class="p-4 w-full {{$width}} {{$hiddenComponent}} grid grid-cols-12 px-4 bg-white dark:bg-gray-800 rounded-lg">
    @foreach($dataSource as $prop)
    @php $prop ? extract($prop) : null; @endphp
        @if($prop)
            @php $readOnly = $hasReadOnly || $readOnly; @endphp
            <div class='col-span-{{$col_span}} {{$hiddenRow}}'>
                <div class='grid grid-row-1'>
                    <div class='grid grid-cols-12 items-center content-start'>
                        @if($columnType === 'static')
                            <div class='col-span-12 text-left'>
                                <x-renderer.item-render-static 
                                        control="{{$control}}"
                                        label="{{$label}}"
                                        title="{{$title}}"
                                        xalign="{{$align}}"
                                        labelExtra="{{$labelExtra}}"
                                    />
                            </div>
                        @else
                            <div class='col-start-1 {{$classColSpanLabel}}  {{$prop['new_line'] === 'true' ? "text-left" : "text-right" }} '>
                                <x-renderer.item-render-label
                                        :item="$item"
                                        :prop="$prop"
                                        
                                        hiddenLabel="{{$hiddenLabel}}"
                                        title="{{$title}}"
                                        control="{{$control}}"
                                        action="{{$action}}"
                                        label="{{$label}}"
                                        isRequired="{{$isRequired}}"
                                        iconJson="{{$iconJson}}"
                                        labelExtra="{{$labelExtra}}"
                                />
                                {{-- @if(!$hiddenLabel)
                                <label class='text-gray-700 dark:text-gray-300  px-3 block text-base' title='{{$title}}'>
                                    @if($control == 'relationship_renderer')
                                    @if($action !== 'create')
                                    @php
                                    $subModel = ($item::$eloquentParams[$prop['columnName']][1]);
                                    $subTable = (new($subModel))->getTable();
                                    $href = "/dashboard/$subTable";
                                    @endphp
                                    <a href="{{$href}}">{{$label}}</a>
                                    @else
                                    <!-- Hide the label -->
                                    @endif
                                    @else
                                    {{$label}}
                                    @endif
                                    @endif
                                    {!!$isRequired ? "<span class='text-red-400'>*</span>" : "" !!}
                                    @if(!$hiddenLabel)
                                    <br />
                                    @endif
                                    <span class="flex justify-end">{!!$iconJson!!}</span>
                                    @if(!$hiddenLabel)
                                    <i>{{$labelExtra}}</i>
                                </label>
                                @endif --}}
                            </div>
                            <div class="{{$classColStart}} {{$classColSpanControl}} py-2 text-left">
                                <x-renderer.item-render-control
                                    :item="$item"
                                    :prop="$prop"
                                    :valueArray="$value"
                                    :value="$value"
                                    
                                    control="{{$control}}"
                                    columnName="{{$columnName}}"
                                    controlExtra="{{$controlExtra}}"
                                    action="{{$action}}"
                                    type="{{$type}}"
                                    readOnly="{{$readOnly}}" 
                                    label="{{$label}}"
                                    placeholder="{{$placeholder}}"
                                    numericScale="{{$numericScale}}"
                                    id="{{$id}}"
                                    modelPath="{{$modelPath}}"
                                    status="{{$status}}"
                                    defaultValue="{{$default_value}}"
                                    columnType="{{$columnType}}"
                                    textareaRows="{{$textareaRows}}"
                                    />
                                {{-- @if (is_null($control))
                                <h2 class="text-red-400">{{"Control of this $columnName has not been set"}}</h2>
                                @endif
                                <!-- Invisible anchor for scrolling when users click on validation fail message -->
                                <strong class="scroll-mt-20 snap-start" id="scroll-{{$columnName}}"></strong>
                                @switch ($control)
                                @case('id')
                                @case('doc_id')
                                <x-controls.id2 name={{$columnName}} value="{{$action === 'edit' ? $value : 'to be generated'}}" />
                                @break
                                @case(App\Utils\Constant::NAME_LOCK_COLUMN)
                                <x-controls.lock-version value={{$value}} />
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
                                @case('toggle')
                                <x-controls.toggle2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('signature')
                                <x-controls.signature2 name={{$columnName}} value={{$value}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('status')
                                @if($status)
                                <x-controls.status-dropdown2 value={{$status}} name={{$columnName}} modelPath={{$modelPath}} readOnly={{$readOnly}} />
                                @endif
                                @break
                                @case ('dropdown')
                                @php
                                    $value = $value ? $value : $default_value;
                                @endphp
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
                                @php
                                $properties = $prop['properties'];
                                @endphp
                                <x-renderer.attachment2 name={{$columnName}} value={{$value}} :properties="$properties" readOnly={{$readOnly}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('comment')
                                <x-controls.comment.comment-group2a commentableId={{$id}} commentableType="{{$type}}" category="{{$columnName}}" readOnly={{$readOnly}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @break
                                @case('signature_multi')
                                <x-controls.signature.signature-group2 :item="$item" signableId={{$id}} signableType="{{$type}}" category="{{$columnName}}" readOnly={{$readOnly}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
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
                                <x-feedback.alert type="warning" title="Control" message="Unknown how to render [{{$control}}/{{$columnName}}]" />
                                @break
                                @endswitch
                                <div component="control-extra" class="text-gray-600 text-sm">{{$controlExtra}}</div> --}}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @endif
    @endforeach
</div>