<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <title>{{ config('app.name', 'Synterra') }}</title> -->
    <title>Synterra toolbox</title>
    <link rel="shortcut icon" href="{{ URL::to('/favicon.ico') }}">
    <!-- <link rel="shortcut icon" type="image/x-icon" href="{{ URL::to('/favicon.ico') }}"/> -->



    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Not allow to print on web -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/print.css') }}" media="print" />
    <link href="sticky-footer.css" rel="stylesheet">

    <style media="screen">
      .top-buffer { margin-top:5px; }
    </style>

</head>
