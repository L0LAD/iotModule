<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="author" content="Audrey DENOUAL">
  <meta name="description" content="IOT Module">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>IOT Module - @yield('title')</title>

  <link rel="icon" href="/img/favicon.svg">

  @section('style')
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

  <link rel="stylesheet" href="/css/styles.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  @show
</head>
<body>
  <div class="container-fluid h-100">
    <aside class="sidebar">
      <a href="#">Dashboard</a>
      <hr />
      <a href="#">Clients</a>
      <a href="#">Places</a>
    </div>
    <!-- <aside class="col-12 col-md-2 p-0 h-100">
    <nav class="navbar navbar-expand navbar-dark flex-md-column flex-row align-items-start">
    <div class="collapse navbar-collapse">
    <ul class="flex-md-column flex-row navbar-nav w-100 justify-content-between">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div id="sidebarLogo"><img src="/img/logo.svg" width=100% alt="Logo"></div>
  </a>

  <div><hr></div>

  <li class="nav-item active">
  <a class="nav-link" href="index.php">
  <span><img src="/img/dashboardLogo.png"></span>
  <span>Dashboard</span>
</a>
</li>

<div><hr></div>

<li class="nav-item active">
<a class="nav-link" href="listModule.php">
<span><img src="/img/moduleLogo.png"></span>
<span>Modules</span>
</a>
</li>

<li class="nav-item active">
<a class="nav-link" href="listPlace.php">
<span><img src="/img/placeLogo.png"></span>
<span>Places</span>
</a>
</li>

</ul>
</div>
</nav>
</aside> -->
</div>

@section('script')
<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
@show
</body>
</html>
