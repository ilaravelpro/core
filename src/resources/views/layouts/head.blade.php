@php
    if (auth()->check() && !session('api-token'))
                session(['api-token' => auth()->user()->createToken('api-web')->accessToken]);
            $token = session('api-token') ? : null;
@endphp
<base href="{{ route('index') }}">
<meta charset="utf-8"/>
<title>{{env('APP_NAME')}}{{ isset($title) ? " | $title" : null }}</title>
<meta name="description" content="{{env('APP_DECS')}}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
@if(isset($token) && $token)
    <meta name="api-token" content="{{ $token }}">
@endif
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
@yield('style-first')
<link href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}" rel="stylesheet" type="text/css"/>
@yield('style-last')
<link rel="shortcut icon" href="{{route('index')}}/favicon.ico"/>
