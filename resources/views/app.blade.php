<!DOCTYPE html>
<html>
    <head>
        <!-- Page Title -->
        <title>Airflix</title>

        <meta charset="utf-8" />
        <meta name="HandheldFriendly" content="True" />
        <meta name="MobileOptimized" content="320" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <meta http-equiv="cleartype" content="on" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <!-- Mobile web app -->
        <meta name="mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="apple-mobile-web-app-title" content="Airflix">

        <!-- Icon -->
        <link rel="shortcut icon" href="touch-icon-iphone.png">

        <!-- iOS icons -->
        <link rel="apple-touch-icon" href="touch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="touch-icon-ipad-retina.png">

        <!-- Styles -->
        <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app" v-cloak></div>

        <!-- Scripts -->
        <script src="{{ elixir('js/app.js') }}"></script>
    </body>
</html>
