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
<div class="container">
    <div class="col-12 text-center p-3">
        <a href="{{ url('/') }}">
            <img src="{{asset('storage/images/site-logo.png')}}" class="img-fluid" alt="meetpat-logo">
        </a>
    </div>
</div>
<!--  -->
    <div class="wrapper">
        <div class="badge-beta">
            <i class="left"></i>
            <i class="right"></i>
            BETA
        </div>
    </div>
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
                            <li><a class="nav-link nav-link-active" href="{{ route('meetpat-client') }}"><i class="fas fa-home"></i>&nbsp;Dashboard</a></li>
                            @else
                            <li><a class="nav-link nav-link-inactive" href="{{ route('meetpat-client') }}"><i class="fas fa-home"></i>&nbsp;Dashboard</a></li>
                            @endif
                            @if(Request::path() == 'meetpat-client/sync-platform')
                            <li><a class="nav-link nav-link-active" href="{{ route('meetpat-client-sync') }}"><i class="fas fa-sync-alt"></i>&nbsp;Sync Platform</a></li>
                            @else
                            <li><a class="nav-link nav-link-inactive" href="{{ route('meetpat-client-sync') }}"><i class="fas fa-sync-alt"></i>&nbsp;Sync Platform</a></li>
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
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/rangeslider.js/2.3.2/rangeslider.min.js"></script>
<script>
    $(document).ready(function() {
        // percentage per month arrays
        var six_months = [20, 20, 20, 15, 12.5, 12.5];
        var twelve_months = [12.5, 12.5, 12.5, 12.5, 10, 10, 5, 5, 5, 5, 5, 5];
        var capital_amounts = [];
        var six_month_json_data = {};
        // Display table function

        var get_factor = function(current_amount_val, current_factor_val) {

            for(var i = 1800; i < current_amount_val + 200; i += 200) 
            {
                current_factor_val -= 0.1020408;
            }

            return current_factor_val;
        }

        var get_table_data = function() {

            $('#tableData').empty();

            var month_selection = parseInt(document.getElementById('period').value);
            var current_amount = parseInt(document.getElementById('slider_js').value);
            var current_factor; // Six months default factor.

            if(month_selection == 6)
            {
                current_factor = 40.00;

                if(current_amount >= 1600)
                {
                    current_factor = 35.00;
                }
            } else {
                current_factor = 50;

                if(current_amount >= 1600)
                {
                    current_factor = 40.00;

                }
            }

            var loan_fee = (current_amount/100)*current_factor;
            var capital_loan = current_amount + loan_fee;

            if(month_selection == 6)
            {
                if(current_amount >= 19200)
                {
                    current_factor_value = 26.02;
                } else {
                    current_factor_value = get_factor(current_amount, current_factor);

                }

                for (var i = 0; i < 6; i++) {

                    if(current_amount > 1600) {

                        loan_fee = (current_amount)*current_factor_value/100;
                        capital_loan = current_amount + loan_fee;

                    } 
                    var month = i + 1;
                    if(i % 2 == 0)
                    {
                        $('#tableData').append("<tr class='table-success'><th>" + month + "</th><td>R" + (capital_loan*six_months[i]/100).toFixed(2) + "</td></tr>");

                    } else {
                        $('#tableData').append("<tr><th>" + month + "</th><td>R" + (capital_loan*six_months[i]/100).toFixed(2) + "</td></tr>");

                    }
                }   

            } else {

                for (var i = 0; i < 12; i++) {

                    if(current_amount >= 17200)
                    {
                        current_factor_value = 32.04;
                    } else {
                        current_factor_value = get_factor(current_amount, current_factor);
                    }

                    if(current_amount > 1600) {
           
                        loan_fee = (current_amount)*current_factor_value/100;
                        capital_loan = current_amount + loan_fee;
                    }
                    
                    var month = i + 1;
                    if(i % 2 == 0)
                    {
                        $('#tableData').append("<tr class='table-success'><th>" + month + "</th><td>R" + (capital_loan*twelve_months[i]/100).toFixed(2) + "</td></tr>");

                    } else {
                        $('#tableData').append("<tr><th>" + month + "</th><td>R" + (capital_loan*twelve_months[i]/100).toFixed(2) + "</td></tr>");

                    }
                    
                }  

            }

            $('#total_repayment').html((capital_loan).toFixed(2));
            // $('#factor').html(current_factor_value);
            // $('#loan_fee').html(loan_fee);
            // $('#capital_loan').html(capital_loan);

        }

        var check_selected_button = function()
        {
            var month_selection = parseInt(document.getElementById('period').value);
            var el_six_month = document.getElementById('sixMBtn');
            var el_twelve_month = document.getElementById('twelveMBtn');

            if(month_selection == 6) {
                el_six_month.classList.remove('btn-outline-success');
                el_six_month.classList.add('btn-success');
                el_twelve_month.classList.add('btn-outline-success');

            } else if(month_selection == 12) {
                el_twelve_month.classList.remove('btn-outline-success');
                el_twelve_month.classList.add('btn-success');
                el_six_month.classList.add('btn-outline-success');
            } else {

            }
        }
        // month button functions
        $('#sixMBtn').click(function() {
            document.getElementById('period').value = 6;
            get_table_data();
            check_selected_button();
        });

        $('#twelveMBtn').click(function() {
            document.getElementById('period').value = 12;
            get_table_data();
            check_selected_button();
        });        
        // Slider
        $('input[type="range"]').rangeslider({
            polyfill: false,

            // Default CSS classes
            rangeClass: 'rangeslider',
            disabledClass: 'rangeslider--disabled',
            horizontalClass: 'rangeslider--horizontal',
            verticalClass: 'rangeslider--vertical',
            fillClass: 'rangeslider__fill',
            handleClass: 'rangeslider__handle',

            // Callback function
            onInit: function() {
                var slider_value = document.getElementById('slider_js').value;
                $('#slide_value').html(slider_value);
                get_table_data();
            },

            // Callback function
            onSlide: function(position, value) {
                $('#slide_value').html(value);
                get_table_data();

            },

            // Callback function
            onSlideEnd: function(position, value) {}
        });
 
        $("#ReferAFriend_BoxContainerBody").appendTo("body");
        $("#ReferAFriend_Open").appendTo("body");
        
        $("#ReferAFriend_Open").click(function(event) {
            event.preventDefault();
            $("#ReferAFriend_BoxContainerBody").animate({right: "0px"});
            $(this).animate({right: "-112px"});
        });
        
        $("#TellAFriend_BoxClose").click(function(event) {
            event.preventDefault();
            $("#ReferAFriend_BoxContainerBody").animate({right: "-320px"});
            $("#ReferAFriend_Open").animate({right: "0px"});
        });
        
        $("#wrapper").click(function() {
            $("#ReferAFriend_BoxContainerBody").animate({right: "-320px"});
            $("#ReferAFriend_Open").animate({right: "0px"}); 
        });
 
    });
  </script>    
@yield('scripts')
</body>
</html>
