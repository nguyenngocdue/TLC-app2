<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'TLC')
<img src="https://assets.website-files.com/61e52058abc83b0e8416a425/61f0ce6fe8161c72f61be858_logo-blue.svg" class="logo" alt="TLC Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
