<style>
    .gallery-info {
    text-align: center;
    display: block;
    font-size: 12px;
    opacity: 0.7;
    font-style: italic;
    }
  /** Below CSS is completely optional **/
    .gallery-item {
    width: 200px;
    padding: 5px;
    }
</style>

<div class="container-sm hidden" component="image-gallery">
    <div class="row justify-content-center">
        <div class="col col-md-10">
            <div class="gallery-container" id="animated-thumbnails-gallery-{{$mode}}">
                @foreach($dataSource as $attachment)
                @php
                    $extension = $attachment['extension'];
                    $mimeType = $attachment['mime_type'];
                    $fileName = $attachment['filename'];
                    $urlMedia = $pathMinio.$attachment['url_media'];
                    $uploadBy = \App\Models\User::findFromCache($attachment['owner_id'])->name ?? '';
                    $urlThumbnail = $urlMedia;
                    $dataVideo = '';
                    $dataIframe=false;
                    switch (strtolower($extension)) {
                        case 'csv':
                        case 'pdf':
                        case 'zip':
                        case 'doc':
                        case 'txt':
                            $dataIframe=true;
                            $urlThumbnail = asset("icons/$extension-128.png");
                            break;
                        case 'mp4':
                        case 'mov':
                            $mimeType ='video/mp4';
                            break;
                        default:
                            break;
                    }
                    if(in_array($mimeType,['video/mp4','video/webm'])){
                        $dataVideo='{"source": [{"src":"'.$urlMedia.'", "type":"'.$mimeType.'"}], "attributes": {"preload": false, "controls": true}}';
                        $urlThumbnail = asset('icons/video-128.png');
                    }
                @endphp
                    <a id="{{$attachment['id']}}" alt="{{$fileName}}" data-lg-size="1280-720" class="gallery-item"
                    {!!$dataVideo ? "":"data-src='$urlMedia'"!!} 
                    data-video="{{$dataVideo}}"
                    data-pinterest-text="{{$fileName}}" 
                    data-tweet-text="lightGallery {{$fileName}}"
                    {!!$dataIframe ? "data-iframe='true'" : ""!!}
                    data-sub-html="
                    <p> File Name: {{$fileName}} </p>
                    <p> Uploaded by: {{$uploadBy}} </p>">
                    <img class="img-responsive" 
                        src="{{$urlThumbnail}}" class="lazyload" />
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    var mode = @json($mode);
    renderGallery(mode);
    function renderGallery(mode){
      $(`#animated-thumbnails-gallery-${mode}`)
        .justifiedGallery({
          captions: false,
          lastRow: "hide",
          rowHeight: 180,
          margins: 5
        })
        .on("jg.complete", function () {
          window.lightGallery(document.getElementById(`animated-thumbnails-gallery-${mode}`), {
            autoplayFirstVideo: false,
            pager: false,
            galleryId: "nature",
            plugins: [
              lgZoom,
              lgThumbnail,
              lgRotate,
              lgFullscreen,
              lgAutoplay,
              lgVideo,
            ],
            mobileSettings: {
              controls: false,
              showCloseIcon: false,
              download: false,
              rotate: false
            }
          });
        });
    }
</script>