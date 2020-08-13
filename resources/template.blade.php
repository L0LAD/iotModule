<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IOT Module - @yield('title')</title>
  @section('style')
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
  @show
</head>
<body>
  <div class="container-fluid h-100">
    <p>Test parent</p>
    @yield('sidebar')
  </div>
</body>
</html>
