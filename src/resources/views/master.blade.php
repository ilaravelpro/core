<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.head', ['title' => $title])
</head>

<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading kt-aside--minimize">
@if(!isset($xhr))
    @include('layouts.mobile')
@endif
<div class="kt-grid kt-grid--hor kt-grid--root">
    @if(isset($xhr))
        @yield('content')
    @else
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            @include('layouts.aside')
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

                @include('layouts.header')
                <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

                    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
                        <div class="kt-container  kt-container--fluid ">
                            @yield('header')
                        </div>
                    </div>


                    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                        @if(isset($alert))
                            <div class="alert alert-{{ isset($alert['color']) ? $alert['color'] : 'light' }} alert-elevate" role="alert">
                                <div class="alert-icon"><i class="{{ isset($alert['icon']) ? $alert['icon'] : 'flaticon-warning' }} kt-font-brand"></i></div>
                                <div class="alert-text">
                                    @yield('alert-content')
                                </div>
                            </div>
                        @endif
                        @yield('content')
                    </div>

                </div>

                @include('layouts.footer')
            </div>
        </div>
    @endif
</div>


@yield('modal')
@include('layouts.modal.scrolltop')

@yield('script-first')
@if(!isset($noscript))
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" type="text/javascript"></script>
@endif
@yield('script-last')
</body>
</html>
