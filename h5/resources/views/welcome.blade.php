<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>魔店</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <script>
            window.Modian = <?php echo json_encode([
                'csrfToken' => csrf_token(),
                'apiDomain' => config('app.url').'/api',
                'currencyPrefix' => env('CURRENCY_PREFIX', '￥')
            ]); ?>

        </script>
    </head>
    <body>
        <div id="app"></div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
