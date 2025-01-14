@switch($extension)
@case("png")
@case("jpeg")
@case("jpg")
@case("gif")
    <i class="fa-file-image {{$class}}"></i>
    @break
@case("mp4")
    <i class="fa-file-video {{$class}}"></i>
    @break
@case("docx")
    <i class="fa-file-word {{$class}}"></i>
    @break
@case("xlsx")
    <i class="fa-file-excel {{$class}}"></i>
    @break
@case("csv")
    <i class="fa-file-csv {{$class}}"></i>
    @break
@case("pdf")
    <i class="fa-file-pdf {{$class}}"></i>
    @break
@case("zip")
    <i class="fa-file-zipper {{$class}}"></i>
    @break
@default
    <i class="fa-file {{$class}}"></i>
    @break
@endswitch