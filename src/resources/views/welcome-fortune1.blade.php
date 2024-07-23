<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0, user-scalable=yes">
  <title>Responsive Design</title>
  <style>
    body {
      margin: 0;
      padding: 0;
    }
    .navbar {
      background-color: #333;
      color: white;
      padding: 1em;
      text-align: center;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
    }
    .container {
      margin-top: 60px; /* Adjust according to your navbar height */
      width: 100%;
      height: auto;
      overflow: hidden;
    }
    .scalable-content {
      font-size: 4vw; /* Adjust this value as needed */
    }
    .scalable-content img {
      width: 100%;
      height: auto;
    }
  </style>
</head>
<body>
  <div class="navbar">
    This is the navbar
  </div>
  <div class="container">
    <div class="scalable-content">
      <p>This is some responsive text that scales with the screen size.</p>
      <img src="{{asset('logo/moduqa.svg')}}" alt="Responsive Image">
    </div>
  </div>
</body>
</html>