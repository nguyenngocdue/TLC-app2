<div class=" grid grid-cols-12 px-4 bg-white dark:bg-gray-800 rounded-lg">
    @foreach($dataSource as $prop)
        @php
        if ($action === "create" && $prop['control'] === 'relationship_renderer') continue;
        $prop ? extract($prop) : null;
        @endphp
        @if($prop)
        <div class='col-span-{{$col_span}} grid'>
            <div class='grid grid-row-1'>
                <div class='grid grid-cols-12 items-center content-start {{$hiddenRow}} '>
                    @if($columnType === 'static')
                    <div class='col-span-12 text-left'>
                        @switch($control)
                            @case('z_page_break')
                            <x-renderer.page-break />
                            @case('z_h1')
                            <x-renderer.heading title="{{$title}}" level=1 align="{{$align}}">{{$label}}</x-renderer.heading>
                            @break
                            @case('z_h2')
                            <x-renderer.heading title="{{$title}}" level=2 align="{{$align}}">{{$label}}</x-renderer.heading>
                            @break
                            @case('z_h3')
                            <x-renderer.heading title="{{$title}}" level=3 align="{{$align}}">{{$label}}</x-renderer.heading>
                            @break
                            @case('z_h4')
                            <x-renderer.heading title="{{$title}}" level=4 align="{{$align}}">{{$label}}</x-renderer.heading>
                            @break
                            @case('z_h5')
                            <x-renderer.heading title="{{$title}}" level=5 align="{{$align}}">{{$label}}</x-renderer.heading>
                            @break
                            @case('z_h6_base')
                            <x-renderer.heading title="{{$title}}" align="{{$align}}">{{$label}}</x-renderer.heading>
                            @break
                            @case('z_divider')
                            <x-renderer.divider />
                            @break

                            @default
                            <x-feedback.alert type="warning" title="{{$title}}" message="The control [{{$control}}] is not available" />
                            @break
                        @endswitch
                    </div>
                    @else
                    <div class='col-start-1 {{$classColSpanLabel}}  {{$prop['new_line'] === 'true' ? "text-left" : "text-right" }} '>
                        @if(!$hiddenLabel)
                        <label class='text-gray-700 dark:text-gray-300  px-3 block text-base' title='{{$title}}'>
                            @if($control == 'relationship_renderer') 
                                @php
                                    $subModel = ($item->eloquentParams[$prop['columnName']][1]);
                                    $subTable = (new($subModel))->getTable();
                                    $href = "/dashboard/$subTable";
                                @endphp
                                <a href="{{$href}}">
                            @endif
                            {{$label}}
                            @if($control == 'relationship_renderer') 
                                </a>
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
                        @endif
                    </div>
                    <div class="{{$classColStart}} {{$classColSpanControl}} py-2 text-left">
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
                            @case(App\Utils\Constant::NAME_LOCK_COLUMN)
                            <x-controls.lock-version value={{$value}} />
                            @break
                            @case('hyperlink')
                            @php $placeholder="https://www.google.com"; @endphp
                            @case('text')
                            @case('thumbnail')
                            <x-controls.text2 name={{$columnName}} value={{$value}} placeholder="{{$placeholder}}" readOnly={{$readOnly}} />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('number')
                            <x-controls.number2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}}/>
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('textarea')
                            <x-controls.textarea2 name={{$columnName}} :value="$value" colType={{$columnType}} placeholder="{{$placeholder}}" readOnly={{$readOnly}}/>
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('toggle')
                            <x-controls.toggle2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('signature')
                            <x-controls.signature2 name={{$columnName}} value={{$value}}/>
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('status')
                                @if($status)
                                <x-controls.status-dropdown2 value={{$status}} name={{$columnName}} modelPath={{$modelPath}} readOnly={{$readOnly}} />
                                @endif
                            @break
                            @case ('dropdown')
                            <x-controls.has-data-source.dropdown2 action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case ('radio')
                            <x-controls.has-data-source.radio-or-checkbox2 action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case ('dropdown_multi')
                            @if($columnName == 'getRoleSet')
                                {{$item->getRoleSet[0]->name}}
                                <x-renderer.button href="/dashboard/admin/setrolesets/{{$id}}/edit" target="_blank">Edit RoleSet in popup</x-renderer.button>
                                @else
                                <x-controls.has-data-source.dropdown2 action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}} />
                                <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                                @endif
                            @break
                            @case('checkbox')
                            <x-controls.has-data-source.radio-or-checkbox2 action={{$action}} type={{$type}} name={{$columnName}} selected={{$value}} readOnly={{$readOnly}} multiple={{true}}/>
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('picker_time')
                            <x-controls.text2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="HH:MM" icon="fa-duotone fa-clock" />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('picker_datetime')
                            <x-controls.text2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="DD/MM/YYYY HH:MM" icon="fa-solid fa-calendar-day" />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('picker_date')
                            <x-controls.date-picker2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} dateTimeType="{{$control}}"/>
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('picker_week')
                            <x-controls.text2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="W01/YYYY" icon="fa-solid fa-calendar-day" />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('picker_month')
                            <x-controls.text2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="MM/YYYY" icon="fa-solid fa-calendar-day" />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('picker_quarter')
                            <x-controls.text2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="Q1/YYYY" icon="fa-solid fa-calendar-day" />
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            @break
                            @case('picker_year')
                            <x-controls.text2 name={{$columnName}} value={{$value}} readOnly={{$readOnly}} placeholder="YYYY" icon="fa-solid fa-calendar-day" />
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

                            @case('relationship_renderer')
                            <x-controls.alert-validation2 name={{$columnName}} label={{$label}} />
                            <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} readOnly={{$readOnly}} :item="$item"/>
                            @break

                            @case('parent_type')
                            <x-controls.parent_type2 type={{$type}} name={{$columnName}} selected="{{$value}}" readOnly={{$readOnly}}/>
                            @break
                            @case('parent_id')
                            <x-controls.parent_id2 type={{$type}} name={{$columnName}} selected="{{$value}}" readOnly={{$readOnly}}/>
                            @break

                            @case('parent_link')
                            <x-feedback.alert type="warning" title="Warning" message="{{$control}} suppose to show in View All screen only, please do not show in Edit screen." />
                            @break

                            @default
                            <x-feedback.alert type="warning" title="Control" message="Unknown how to render [{{$control}}/{{$columnName}}]" />
                            @break
                        @endswitch
                        {{$controlExtra}}
                    </div>
                    @endif
                </div>

            </div>
        </div>
        @endif
    @endforeach
</div>