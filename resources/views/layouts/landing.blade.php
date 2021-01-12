<!DOCTYPE html>
<!--
Landing page based on Pratt: http://blacktie.co/demo/pratt/
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Facility access management">
    <meta name="author" content="Faculty of science - science.uottawa.ca">

    <meta property="og:title" content="Facility access management" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="Facility access management" />
    <meta property="og:url" content="https://fam.uottawa.ca/" />
    <meta property="og:sitename" content="fam.uottawa.ca" />

    <title>Facility Access Management (FAM)</title>

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/all-landing.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="{{ url (mix('/js/app-landing.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('js/moment.min.js')}}"></script>
</head>

<body data-spy="scroll" data-target="#navigation" data-offset="50">
 
<div id="app" v-cloak>
    <!-- Fixed navbar -->
    <div id="navigation" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand font-size-title" href="/"><b>
                @if (App::isLocale('fr'))
                  {{ config('app.name_page_fr') }}
                @else
                  {{ config('app.name_page') }}
                @endif
                </b>
                <!--<b>Facility access management (FAM) </b>-->
                </a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                        <!-- Mobile -->
                        <li class="hide_on_desktop"><a href="/welcome/en">@if (!App::isLocale('fr')) <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> @endif En</a></li>
                        <li class="hide_on_desktop"><a href="/welcome/fr">@if (App::isLocale('fr')) <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> @endif Fr</a></li> 
                        <!-- Not mobile -->
                        <li class="hide_on_mobile"><a href="/welcome/en">@if (!App::isLocale('fr')) <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> @endif English</a></li>
                        <li class="hide_on_mobile"><a href="/welcome/fr">@if (App::isLocale('fr')) <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> @endif Français</a></li> 
                        
                        <li><a href="/help_front"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> {{ __("help") }}</a></li> 
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">{{ trans('adminlte_lang::message.login') }}</a></li>
                    @else
                        <li><a href="/">{{ Auth::user()->email }}</a></li>
                        <li><a href="{{ url('/logout') }}"> {{ __('logout') }} </a></li>
                    @endif
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>

    <section id="desc" name="desc">
        <!-- Your Page Content Here --> 
        @yield('main-content')
    </section>
    <footer> 
        <div id="c">
            <div class="container">
                <p>
                    @if (!App::isLocale('fr'))
                      <b>{{ env('APP_NAME', 'test') }}</b>. 
                    @else
                      <b>{{ env('APP_NAME_FR', 'test_fr') }}</b>. 
                    @endif
                </p>
            </div>
        </div>
    </footer>

</div>
 
<script type="text/javascript">
  @yield ('scripts')
</script> 

</body>
</html>
