<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    <div  style="width: 240px; height: 40px; object-fit: cover;">
        <img src="{{asset('/logo/tlc.png')}}" class="logo" alt="TLC Logo">
    </div>
    {{ $slot }}
</a>
</td>
</tr>
