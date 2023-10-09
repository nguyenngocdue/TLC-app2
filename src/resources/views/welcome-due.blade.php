@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scroll Example</title>
    <style>
        /* Thêm một phần margin để chúng ta có thể thấy được hiệu ứng scroll */
        body {
            margin-top: 200px;
        }
    </style>
</head>

<body>
    <!-- Liên kết trỏ đến id của phần tử cần cuộn đến -->
    <a href="#section2">Scroll to Section 2</a>

    <!-- Phần tử chúng ta muốn cuộn (scroll) đến -->
    <div id="section2" style="height: 500px; background-color: lightgray;">
        Section 2 Content
    </div>
</body>

</html>


@endsection
