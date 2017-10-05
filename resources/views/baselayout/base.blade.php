<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@if( isset($meta['title'])) {{ $meta['title']}} @else Homolog Comparison Tools @endif</title>
    <meta name="description" content="@if( isset($meta['description'])) {{ $meta['description']}} @endif">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token()}}" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ URL::asset('/public/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/public/css/animate.css') }}" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">


</head>
<body>
<header>
    <div class="container">
        <div class="logo pull-left animated">
  <span class="site-name">Homolog Comparison Tool</span>
        </div>
    </div>
</header>


<section class="outer-container">
    @yield('content')
</section>




     <script type="text/javascript" src="{{ URL::asset('public/js/app.js') }}"></script>


</body>
</html>
