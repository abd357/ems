<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        @vite('resources/css/app.css') 
    </head>
    <body class="font-sans antialiased dark:bg-gray-600 dark:text-white/50">
        <div id="app" class="dark:text-white"></div>
        @vite('resources/js/app.js') 
    </body>
</html>
