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
    <link rel="shortcut icon" href="{{ Storage::disk('s3')->url('dashboard.meetpat/public/images/Minimal Logo.png') }}">
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
                    @if(Request::path() == '/')
                    <li class="active"><a class="nav-link nav-link-active" href="/"><i class="fas fa-home"></i></a></li>
                    @else
                    <li><a class="nav-link" href="/"><i class="fas fa-home"></i></a></li>
                    @endif
                    @guest

                    @else
                        @if(\Auth::user()->admin)
                        <!-- Administrators Navigation --> 
                            <li class="nav-item dropdown admin-dropdowns">
                                @if(Request::path() == 'meetpat-admin/clients' or Request::path() == 'meetpat-admin/clients/create')
                                <a class="nav-link dropdown-toggle nav-link-active" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-users"></i>&nbsp;Clients</a></a>
                                @else
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-users"></i>&nbsp;Clients</a></a>
                                @endif
                                <div class="dropdown-menu">
                                    @if(Request::path() == 'meetpat-admin/clients')
                                    <a class="dropdown-item active-item" href="#"><i class="fas fa-users-cog"></i>&nbsp;Clients</a>
                                    @else
                                    <a class="dropdown-item" href="/meetpat-admin/clients"><i class="fas fa-users-cog"></i>&nbsp;Clients</a>
                                    @endif

                                    @if(Request::path() == 'meetpat-admin/clients/create')
                                    <a class="dropdown-item active-item" href="#"><i class="fas fa-user-plus"></i>&nbsp;Add Client</a>
                                    @else
                                    <a class="dropdown-item" href="/meetpat-admin/clients/create"><i class="fas fa-user-plus"></i>&nbsp;Add Client</a>
                                    @endif                                    
                                </div>
                            </li>
                            <li class="nav-item dropdown admin-dropdowns">
                                @if(Request::path() == 'meetpat-admin/resellers' or Request::path() == 'meetpat-admin/resellers/create')
                                <a class="nav-link dropdown-toggle nav-link-active disabled" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-tie"></i>&nbsp;Resellers</a></a>

                                @else
                                <a class="nav-link dropdown-toggle disabled" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-tie"></i>&nbsp;Resellers</a></a>
                                @endif
                            
                                <div class="dropdown-menu">
                                    @if(Request::path() == 'meetpat-admin/resellers')
                                    <a class="dropdown-item active-item" href="#"><i class="fas fa-users-cog"></i>&nbsp;Resellers</a>
                                    @else
                                    <a class="dropdown-item" href="/meetpat-admin/resellers"><i class="fas fa-users-cog"></i>&nbsp;Resellers</a>
                                    @endif

                                    @if(Request::path() == 'meetpat-admin/resellers/create')
                                    <a class="dropdown-item active-item" href="#"><i class="fas fa-user-plus"></i>&nbsp;Add Reseller</a>
                                    @else
                                    <a class="dropdown-item" href="/meetpat-admin/resellers/create"><i class="fas fa-user-plus"></i>&nbsp;Add Reseller</a>
                                    @endif                                    
                                </div>
                            </li>
                            @if(Request::path() == 'meetpat-admin/enriched-data-tracking')
                            <li class="active"><a class="nav-link nav-link-active" href="/meetpat-admin/enriched-data-tracking"><i class="fas fa-chart-line"></i>&nbsp;Enriched Data Tracking</a></li>
                            @else
                            <li><a class="nav-link" href="/meetpat-admin/enriched-data-tracking"><i class="fas fa-chart-line"></i>&nbsp;Enriched Data Tracking</a></li>
                            @endif
                        @endif
                        
                        @if(\Auth::user()->client)
                        <!-- Clients Navigation --> 
                            @if(Request::path() == 'meetpat-client/upload')
                            <li class="active"><a class="nav-link nav-link-active" href="{{ route('upload-main') }}"><i class="fas fa-file-upload"></i>&nbsp;Upload Client Data</a></li>
                            @else
                            <li><a class="nav-link nav-link-inactive" href="{{ route('upload-main') }}"><i class="fas fa-file-upload"></i></i>&nbsp;Upload Client Data</a></li>
                            @endif
                            @if(Request::path() == 'meetpat-client/data-visualisation')
                            <li class="active"><a class="nav-link nav-link-active" href="{{ route('meetpat-data-visualisation') }}"><i class="fas fa-chart-line"></i>&nbsp;Dashboard</a></li>
                            @else
                            <li><a class="nav-link nav-link-inactive" href="{{ route('meetpat-data-visualisation') }}"><i class="fas fa-chart-line"></i>&nbsp;Dashboard</a></li>
                            @endif
                        @endif
                    @endguest

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest

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
                                    <!-- {{ Auth::user()->name }} --><i class="far fa-user-circle"></i>&nbsp;&nbsp;Account <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @if(\Auth::user()->admin()->first())
                                <a class="dropdown-item" href="{{ route('meetpat-admin') }}">
                                        {{ __('Admin') }}
                                </a>
                                @endif
                                    <span class="dropdown-item account-name">{{\Auth::user()->name}}</span>
                                         @if(\Auth::user()->client)
                                         <span class="dropdown-item account-credits">Credits
                                            @if(\Auth::user()->client_uploads)
                                                <span class="badge badge-pill badge-primary">{{number_format(\Auth::user()->client_uploads->uploads)}}/{{number_format(\Auth::user()->client_uploads->upload_limit)}}</span>
                                            @else
                                                <span class="badge badge-pill badge-primary">0/0</span>
                                            @endif
                                        </span>
                                        @endif
                                    @if(\Auth::user()->client()->first())
                                    <a class="dropdown-item account-item" href="/meetpat-client/settings">
                                        Settings
                                    </a>
                                    <a class="dropdown-item account-item" href="/meetpat-client/files">
                                        Files
                                    </a>
                                    @endif
                                    <a class="dropdown-item account-item" href="{{ route('logout') }}"
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
            @include('flash-message')

            @yield('content')
        </main>
    </div>
    <nav class="navbar navbar-expand-lg sticky-bottom navbar-light bg-light">
        <a class="navbar-brand" href="#"><img src="{{Storage::disk('s3')->url('meetpat/public/images/site-logo.png')}}" height="50px" width="auto" alt="meetpat-logo"></a>
        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item"><a class="nav-link" href="/privacy-policy">Privacy Policy</a></li>
            <li class="nav-item"><a class="nav-link" href="/terms">Terms</a></li>
        </ul>
    </nav>
    @yield('modals')
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    @yield('scripts')
</body>
</html>
