<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>魔店服务商后台</title>
    <!-- Styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/font-awesome.css" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script>
        window.Modian = {!! json_encode([
            'csrfToken' => csrf_token(),
            'siteName'  => config('app.name'),
            'baseDomain' => config('app.url'),
            'apiDomain' => config('app.url').'/api',
            'uploadAction' => env('QINIU_UPLOAD_DOMAIN'),
            'qiniuDomain' => env('QINIU_DOMAIN'),
            'currencyPrefix' => env('CURRENCY_PREFIX', '￥')
        ]) !!}
    </script>
</head>
<body>
<div id="app"></div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
