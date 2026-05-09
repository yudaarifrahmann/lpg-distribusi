<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'LPG Distribution')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('extra_css')
</head>
<body class="bg-gray-50">
    @yield('content')
    @yield('extra_js')
</body>
</html>
