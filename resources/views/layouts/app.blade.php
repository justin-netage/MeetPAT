<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div clas="container">
        <div class="col-12 text-center p-5">
            <a href="{{ url('/') }}">
                <img src="{{asset('storage/images/site-logo.png')}}" class="img-fluid" alt="meetpat-logo">
            </a>
        </div>
    </div>
    <div id="app">
        <nav class="navbar sticky-top navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!-- {{ config('app.name', 'Laravel') }} -->
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if(Request::path() == 'how-it-works')
                        <!-- <li><a class="nav-link nav-link-active" href="{{ route('how-it-works') }}">How it works</a></li> -->
                        @else
                        <!-- <li><a class="nav-link nav-link-inactive" href="{{ route('how-it-works') }}">How it works</a></li> -->
                        @endif
                        @if(Request::path() == 'benefits')
                        <!-- <li><a class="nav-link nav-link-active" href="{{ route('benefits') }}">Benefits</a></li> -->
                        @else
                        <!-- <li><a class="nav-link nav-link-inactive" href="{{ route('benefits') }}">Benefits</a></li> -->
                        @endif
                        @if(Request::path() == 'insights')
                        <!-- <li><a class="nav-link nav-link-active" href="{{ route('insights') }}">Insights</a></li> -->
                        @else
                        <!-- <li><a class="nav-link nav-link-inactive" href="{{ route('insights') }}">Insights</a></li> -->
                        @endif
                        @if(Request::path() == 'onboarding')
                        <!-- <li><a class="nav-link nav-link-active" href="{{ route('onboarding') }}">Onboarding</a></li> -->
                        @else
                        <!-- <li><a class="nav-link nav-link-inactive" href="{{ route('onboarding') }}">Onboarding</a></li> -->
                        @endif
                        @if(Request::path() == 'pricing')
                        <!-- <li><a class="nav-link nav-link-active" href="{{ route('pricing') }}">Pricing</a></li> -->
                        @else
                        <!-- <li><a class="nav-link nav-link-inactive" href="{{ route('pricing') }}">Pricing</a></li> -->
                        @endif

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <!-- <li class="nav-item"> -->
                                @if(Request::path() == 'apply')
                                <!-- <a class="nav-link nav-link-active" href="{{ route('apply') }}">{{ __('Apply') }}</a> -->
                                @else
                                <!-- <a class="nav-link nav-link-inactive" href="{{ route('apply') }}">{{ __('Apply') }}</a> -->
                                @endif
                            <!-- </li> -->
                            <li class="nav-item">
                                    @if(Request::path() == 'contact')
                                    <a class="nav-link nav-link-active" href="{{ route('contact') }}">{{ __('Contact') }}</a>

                                    @else
                                        <a class="nav-link nav-link-inactive" href="{{ route('contact') }}">{{ __('Contact') }}</a>
                                    @endif
                            </li>
                            <li class="nav-item">
                                    @if(Request::path() == 'Login')
                                    <a class="nav-link nav-link-active" href="{{ route('login') }}">{{ __('Login') }}</a>

                                    @else
                                        <a class="nav-link nav-link-inactive" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    @endif
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
