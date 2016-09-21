<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="IllSim">

        <title>@yield('title')</title>

        <!-- Styles -->
        <link media="all" type="text/css" rel="stylesheet" href="/css/app.css">
    </head>

    <body>
        @yield('content')
    </body>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/extra.js"></script>
    <script src="/js/vendor/vis/vis.min.js"></script>
    <script src="/js/vendor/materialize/materialize.min.js"></script>
</html>
