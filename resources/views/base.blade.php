<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ rtrim(url('/'), '/') }}">
    <meta name="google" content="notranslate" />

    <!-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@3.x/css/materialdesignicons.min.css" rel="stylesheet"> -->
    <title translate="no">Martí Pomares, S.L</title>
    <link rel="icon" type="image/png" href="/logo_menu.png" />
    <!-- Add Roboto font -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Mono:300,400,500,700">
    <!-- Add Material Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    <!-- quill editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    @vite(['src/main.js'])
</head>

<body>
    <div id="app"></div>
    <script type="text/javascript" src="{{ url('js/moment.min.js') }}"></script>
</body>

</html>
