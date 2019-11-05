<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="robots" content="noindex" />
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>An Asterism - Ohio Bourbon Watch</title>
    <link rel="stylesheet" href="/css/frontend.css">
    @yield('stylesheets')
</head>
<body>

    <div id="logo"></div>

    @yield('javascript')
</body>
</html>