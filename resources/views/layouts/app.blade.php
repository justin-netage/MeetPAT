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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    @yield('styles')
</head>
<body>
    @yield('side-bars')
<div class="container">
    <div class="col-12 text-center p-3">
        <a href="{{ url('/') }}">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/site-logo.png')}}" class="img-fluid" alt="meetpat-logo">
        </a>
    </div>
</div>
<!-- Beta Ribbon  -->
<div class="corner-ribbon top-left shadow">Beta</div>
<!-- -->
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
                    @guest

                    @else
                        @if(\Auth::user()->admin)
                        <!-- Administrators Navigation --> 
                            @if(Request::path() == 'meetpat-admin/users')
                            <li><a class="nav-link nav-link-active" href="{{ route('meetpat-users') }}"><i class="fas fa-users-cog"></i>&nbsp;Users</a></li>
                            @else
                            <li><a class="nav-link nav-link-inactive" href="{{ route('meetpat-users') }}"><i class="fas fa-users-cog"></i>&nbsp;Users</a></li>
                            @endif
                            @if(Request::path() == 'meetpat-admin/users/create')
                            <li><a class="nav-link nav-link-active" href="{{ route('create-user') }}"><i class="fas fa-user-plus"></i>&nbsp;New User</a></li>
                            @else
                            <li><a class="nav-link nav-link-inactive" href="{{ route('create-user') }}"><i class="fas fa-user-plus"></i>&nbsp;New User</a></li>
                            @endif
                        @endif
                        
                        @if(\Auth::user()->client)
                        <!-- Clients Navigation --> 
                            @if(Request::path() == 'meetpat-client')
                            <li class="active"><a class="nav-link nav-link-active" href="{{ route('meetpat-client') }}"><i class="fas fa-home"></i>&nbsp;Home</a></li>
                            @else
                            <li><a class="nav-link nav-link-inactive" href="{{ route('meetpat-client') }}"><i class="fas fa-home"></i>&nbsp;Home</a></li>
                            @endif
                            @if(Request::path() == 'meetpat-client/sync/facebook' or Request::path() == 'meetpat-client/sync/google')
                            <li class="nav-item dropdown active">
                                <a class="nav-link dropdown-toggle nav-link-active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-sync-alt"></i>&nbsp;Sync Platform
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @if(Request::path() == 'meetpat-client/sync/facebook')
                                    <a class="dropdown-item active" href="/meetpat-client/sync/facebook">{{ __('Facebook') }}</a>
                                @else
                                    <a class="dropdown-item" href="/meetpat-client/sync/facebook">{{ __('Facebook') }}</a>
                                @endif
                                @if(Request::path() == 'meetpat-client/sync/google')
                                    <a class="dropdown-item active" href="/meetpat-client/sync/google">{{ __('Google') }}</a>
                                @else
                                    <a class="dropdown-item" href="/meetpat-client/sync/google">{{ __('Google') }}</a>
                                @endif
                                </div>
                            </li>
                            @else      
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-sync-alt"></i>&nbsp;Sync Platform
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @if(Request::path() == 'meetpat-client/sync/facebook')
                                    <a class="dropdown-item active" href="/meetpat-client/sync/facebook">{{ __('Facebook') }}</a>
                                @else
                                    <a class="dropdown-item" href="/meetpat-client/sync/facebook">{{ __('Facebook') }}</a>
                                @endif
                                @if(Request::path() == 'meetpat-client/sync/google')
                                    <a class="dropdown-item active" href="/meetpat-client/sync/google">{{ __('Google') }}</a>
                                @else
                                    <a class="dropdown-item" href="/meetpat-client/sync/google">{{ __('Google') }}</a>
                                @endif
                                </div>
                            </li>
                            @endif
                            @if(Request::path() == 'meetpat-client/upload-client-file-data')
                            <li class="active"><a class="nav-link nav-link-active" href="{{ route('upload-client-data') }}"><i class="fas fa-file-upload"></i>&nbsp;Upload Customers</a></li>
                            @else
                            <li><a class="nav-link nav-link-inactive" href="{{ route('upload-client-data') }}"><i class="fas fa-file-upload"></i></i>&nbsp;Upload Customers</a></li>
                            @endif
                        @endif
                    @endguest
                        
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
                                @if(\Auth::user()->admin()->first())
                                <a class="dropdown-item" href="{{ route('meetpat-admin') }}">
                                        {{ __('Admin') }}
                                </a>
                                @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    @if(Request::path() == 'contact')
                                    <a class="dropdown-item active" href="{{ route('contact') }}">{{ __('Contact') }}</a>
                                    @else
                                    <a class="dropdown-item" href="{{ route('contact') }}">{{ __('Contact') }}</a>
                                    @endif
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>

                            </li>
                            @if(\Auth::user()->client)
                            <li class="nav-item">
                                <a class="nav-link">credits used&nbsp;<span class="badge badge-pill badge-primary">
                                @if(\Auth::user()->client_uploads)
                                    {{\Auth::user()->client_uploads->uploads}} / 10000
                                @else
                                    0 / 10000
                                @endif
                                </span></a>
                            </li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @include('flash-message')

            @yield('content')
        </main>
    </div>
    @yield('modals')
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    @yield('scripts')
</body>
</html>
