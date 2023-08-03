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
<div class="container-sm">
    <div class="row justify-content-center">
        <div class="col col-md-10">
            <div class="gallery-container" id="animated-thumbnails-gallery">
                @foreach($dataSource as $attachment)
                @php
                    $extension = $attachment['extension'];
                    $minType = $attachment['mime_type'];
                    $fileName = $attachment['filename'];
                    $urlMedia = $pathMinio.$attachment['url_media'];
                    $uploadBy = \App\Models\User::findFromCache($attachment['owner_id'])->name ?? '';
                    $urlThumbnail = $urlMedia;
                    $dataVideo = '';
                    switch ($extension) {
                        case 'csv':
                            $urlThumbnail = asset('icons/csv-256.png');
                            break;
                        case 'pdf':
                            $urlThumbnail = asset('icons/pdf-256.png');
                            break;
                        case 'zip':
                            $urlThumbnail = asset('icons/zip-256.png');
                            break;
                        case 'mp4':
                        case 'webm':
                            $dataVideo='{"source": [{"src":"'.$urlMedia.'", "type":"'.$minType.'"}], "attributes": {"preload": false, "controls": true}}';
                            break;
                        default:
                            break;
                    }
                @endphp
                    <a id="{{$attachment['id']}}" alt="{{$fileName}}" data-lg-size="1280-720" class="gallery-item"
                    {{$dataVideo ? "":"data-src=$urlMedia"}} 
                    data-video="{{$dataVideo}}"
                    data-pinterest-text="{{$fileName}}" data-tweet-text="lightGallery {{$fileName}}"
                    data-sub-html="
                    <p> File Name: {{$fileName}} </p>
                    <p> Upload by: {{$uploadBy}} </p>">
                    <img class="img-responsive" 
                        src="{{$urlThumbnail}}" />
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    $("#animated-thumbnails-gallery")
      .justifiedGallery({
        captions: false,
        lastRow: "hide",
        rowHeight: 180,
        margins: 5
      })
      .on("jg.complete", function () {
        window.lightGallery(document.getElementById("animated-thumbnails-gallery"), {
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
</script>