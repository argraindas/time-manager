<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">

    @env('production')
        <link href="{{ mix('css/home.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    @endenv

</head>
<body class="text-center">

    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
            <div class="inner">
                <h3 class="masthead-brand">Time manager</h3>
                <nav class="nav nav-masthead justify-content-center">
                    <a class="nav-link active" href="#">Home</a>
                    <a class="nav-link" href="#">Features</a>
                    <a class="nav-link" href="#">Contact</a>
                </nav>
            </div>
        </header>

        <main role="main" class="inner cover">
            <h1 class="cover-heading">Hi there!</h1>
            <p class="lead">
                Welcome to my tiny time manager tool. You can record your daily activities and track where did you spent most of your time.
            </p>
            <p class="lead">
                <a href="{{ @route('login') }}" class="btn btn-md btn-primary">Login</a>
                <a href="{{ @route('register') }}" class="btn btn-md btn-secondary ml-2">Register</a>
            </p>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <p>All rights reserved © 2019</p>
            </div>
        </footer>
    </div>

</body>
</html>
