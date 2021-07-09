<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    {{--<script src="{{ asset('js/app.js') }}" defer></script>--}}


    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <!-- Custom fonts for this template-->
    <link href="{{asset('includes/sbadmin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="{{asset('includes/sbadmin/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <style>
        * {
            font-size: 14px;
        }
    </style>
    @yield('styles')
</head>
<body id="page-top" class="@yield('body_class')">
<div id="app">
        @yield('content')
</div>

<!-- Core plugin JavaScript-->
<script src="{{ asset('includes/sbadmin/vendor/jquery/jquery.slim.min.js') }}"></script>
<script src="{{asset('includes/sbadmin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Bootstrap core JavaScript-->
<script src="{{asset('includes/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>


<!-- Custom scripts for all pages-->
<script src="{{asset('includes/sbadmin/js/sb-admin-2.min.js')}}"></script>
@yield('scripts')
</body>
</html>
