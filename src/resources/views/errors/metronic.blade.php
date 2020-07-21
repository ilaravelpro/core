@extends('master', ['title' => $title, 'auth' => true])

@section('style-first')
    <link href="/skins/metronic/css/pages/error/error-6.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v6" style="background-image: url(/images/error/bg6.jpg);">
        <div class="kt-error_container">
            <div class="kt-error_subtitle kt-font-light">
                <h1>{{ $code }}</h1>
            </div>
            <p class="kt-error_description kt-font-light">
                {{ $message }}
                <div class="mb-lg-5"></div>
                <a class="btn btn-danger btn-wide" href="{{ route('index') }}">Go Home</a>
            </p>
        </div>
    </div>
@endsection

@section('script-first')
    <script>
        var BitAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": [
                        "#c5cbe3",
                        "#a1a8c3",
                        "#3d4465",
                        "#3e4466"
                    ],
                    "shape": [
                        "#f0f3ff",
                        "#d9dffa",
                        "#afb4d4",
                        "#646c9a"
                    ]
                }
            }
        };
    </script>
@endsection
