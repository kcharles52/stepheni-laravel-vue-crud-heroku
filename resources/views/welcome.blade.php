<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Ld-Talk') }}</title>
        <meta name="application-name" content="{{ config('app.name', 'Ld-Talk') }}">
        <!-- Fonts -->
        <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
        <!-- Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- Css -->
        <link href="{{ asset('css/custom.css') }}" type="text/css" rel="stylesheet">
        <link href="{{ asset('css/normalize.css') }}" type="text/css" rel="stylesheet">
        <link href="{{ asset('css/skeleton.css') }}" type="text/css" rel="stylesheet">
    </head>
    <body>
      <div id="app">
        <App></App>
      </div>
      <!-- Javascript -->
      <script src="{{ asset('js/app.js') }}" charset="utf-8"></script>
    </body>
</html>
