<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'TLC')
<img src="{{asset('/logo/tlc.png')}}" class="logo" alt="TLC Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
