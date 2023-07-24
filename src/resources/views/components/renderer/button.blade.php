@if($href && !$disabled) <a href="{{$href}}" target="{{$target}}"> @endif
@php $innerHTML=($icon?"<i class='$icon mr-1'></i>":"").$slot;  @endphp
<button type="{{$htmlType}}" id="{{$id}}" name="{{$name}}" value="{{$value}}" 
    title="{{$title}}" class="{{$className}}" 
    onclick="{!! $disabled ? "" : $onClick !!}" @click="{!! $disabled ? "" : $click !!}" 
    @keydown.escape="{!! $keydownEscape !!}" accesskey="{{$accesskey}}" @disabled($disabled)
    >@php echo $innerHTML; @endphp</button> 
{{-- IMPORTANT: DO NOT ENTER between <button> and innerHTML, otherwise button.firstChild will be wrong, icon of trash in Editable Table will not change --}}
@if($href  && !$disabled) </a> @endif    