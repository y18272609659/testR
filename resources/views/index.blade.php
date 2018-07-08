<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>繁盛王国</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('css/app.css?v='.time()) }}">
    </head>
    <body>
    <script>
        const params = {
          // mainSite: 'http://127.0.0.1:8000',
          mainSite: 'http://www.nice-kingdom.com',
        }
    </script>

    @if (!Auth::check())
        <script>
          localStorage.clear();
          localStorage.setItem('loginCheck', 'false');
        </script>
    @else
        <script>
          localStorage.setItem('loginCheck', 'true');
        </script>
    @endif

    <div id="app"></div>

    <script src="{{ asset('js/app.js?v='.time()) }}"></script>
    </body>
</html>
