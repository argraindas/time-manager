<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">

    @env('production')
        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vendor.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @else
        <script src="{{ asset('js/app.js') }}" defer></script>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endenv

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
            'user' => Auth::user(),
            'signedIn' => Auth::check()
        ]) !!};
    </script>

    @yield('head')
</head>
<body>
    <div id="app">

        <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{ route('dashboard') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <input class="form-control form-control-dark col-md-2" type="text" placeholder="Search" aria-label="Search">
            <ul class="navbar-nav col-md-8 px-3">
                @guest
                    <li class="nav-item text-nowrap px-3">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li c.navbar-navlass="nav-item text-nowrap px-3">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else

                    <li class="nav-item text-nowrap px-3">
                        Hi {{ Auth::user()->name }}!
                    </li>
                    <form method="post" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <li class="nav-item text-nowrap px-3">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Sign out') }}
                            </a>
                        </li>
                    </form>
                @endguest
            </ul>
        </nav>

        <div class="container-fluid">
            <div class="row">

                @include('_partials.sidebar-left')

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('title')</h1>
                    </div>

                    @if(session('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @yield('content')

                </main>
            </div>
        </div>

        <flash message="{{ session('flash') }}"></flash>

    </div>

</body>
</html>
