@if(in_array($extension,["png","gif","jpg","jpeg"]))
    <div title="{{$fileName}}">
        <img src="{{$urlMedia}}" alt="{{$fileName}}"/>
    </div>
@elseif(in_array($extension,["csv","pdf","zip"]))
    <div>
        <a type="external" to="{{$urlMedia}}" class="" target="_blank" rel="noopener noreferrer" href="{{$urlMedia}}"> 
            <span role="img" aria-label="file" class="anticon anticon-file">
                <svg viewBox="64 64 896 896" focusable="false" data-icon="file" width="1em" height="1em" fill="currentColor" aria-hidden="true">
                    <path d="M854.6 288.6L639.4 73.4c-6-6-14.1-9.4-22.6-9.4H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V311.3c0-8.5-3.4-16.7-9.4-22.7zM790.2 326H602V137.8L790.2 326zm1.8 562H232V136h302v216a42 42 0 0042 42h216v494z">
                    </path>
                </svg>
            </span>
            {{$fileName}}
        </a>
    </div>
@elseif($extension == 'mp4')
<div title="{{$fileName}}">
    <video src="{{$urlMedia}}"></video>
</div>
@else
@endif


