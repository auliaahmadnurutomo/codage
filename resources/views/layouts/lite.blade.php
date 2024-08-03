<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME', 'Laravel') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{asset('theme/bootstrap/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{asset('theme/js/jquery-3.4.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('theme/bootstrap/bootstrap.min.js')}}"></script>
</head>
<body>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
    
</body>
</html>
