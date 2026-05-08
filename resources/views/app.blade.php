<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LPG Distribution')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('extra_css')
</head>
<body class="bg-gray-50">
    @yield('content')
    @yield('extra_js')
</body>
</html>
