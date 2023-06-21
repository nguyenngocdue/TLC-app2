{{-- @dump($allApps) --}}
<input name='txtKeywork' value=''>
<br/>
<ul>
@foreach($allApps as $appgroup)
  <li>{{( $appgroup['title'])}}</li>
  <ul>
  @foreach($appgroup['children'] as $app)
    <li>{{($app['title'])}}</li>
  <ul>
    @foreach($app['children'] as $config)
   <li>
      <a href="{{$config['href']}}">{{($config['title'])}}</a>
       - ({{$config['hidden'] ?? ""}})
       - {{($config['package'])}}
       - {{($config['sub_package'])}}
    </li>
  
    @endforeach
  </ul>

  @endforeach
  </ul>
  <br/>
@endforeach
</ul>