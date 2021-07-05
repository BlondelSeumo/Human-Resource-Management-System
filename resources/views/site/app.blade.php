<!DOCTYPE html>
<html lang="en" xmlns:og="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="Cloud based HR Management solution for small and medium businesses with which you can manage leaves, attendance, payroll, expenses, awards and employee information"/>

    <meta name="keywords"
          content="hr software cloud, hr leave management software, web based hr software, hr cloud software, free hr management software, web based hr management software, small business hr software, simple hr software, hr cloud solutions, easy hr software, cloud based hr software, online hr software, hr software small business, cloud hr solutions, hr software for small business, saas hr software, hr management software, web hr software, hr software online, free hr software, hr software for sme, hr management software for small business, cloud hr software, online hr management software"/>
    <title>{{$setting->main_name}}</title>

    <!-- core CSS -->
    <link href="{{ asset('assets/site/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/site/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/site/css/animate.min.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('assets/site/css/prettyPhoto.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('assets/site/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/site/css/responsive.min.css') }}" rel="stylesheet">
<!--[if lt IE 9]>
    <script src="{{ asset('assets/site/js/html5shiv.js') }}"></script>
    <script src="{{ asset('assets/site/js/respond.min.js') }}"></script>
    <![endif]-->
    <link rel="icon" href="{{ $setting->favicon_image_url }}" sizes="16x16">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="{{ asset('assets/site/images/ico/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="{{ asset('assets/site/images/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="{{ asset('assets/site/images/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed"
          href="{{ asset('assets/site/images/ico/apple-touch-icon-57-precomposed.png') }}">

    <meta property="og:title" content="HRM - Cloud HR Software for Small and Medium Businesses"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ isset($_SERVER["HTTPS"]) ? 'https' : 'http' }}://{{ $_SERVER['HTTP_HOST'] }}"/>
    <meta property="og:image" content="{{ asset("assets/site/images/ogimage.png") }}"/>
    <meta property="og:site_name" content="HRM"/>
    <meta property="og:description"
          content="Cloud based HR Management solution for small and medium businesses with which you can manage leaves, attendance, payroll, expenses, awards and employee information"/>

    @yield("css")
</head><!--/head-->

<body class="homepage">

<header id="header">
    <nav
        class="navbar navbar-inverse navbar-fixed-top @if(\Route::is('home')) navbar-home @else navbar-fixed-top @endif"
        role="banner">
        <div @if(!isset($demo)) class="container" @else class="container-fluid"
             style="padding-right: 10px; padding-left: 10px;" @endif>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route("home") }}">
                    <img src="{{$setting->logo_image_url}}" height="50px">
                </a>
            </div>

            <div class="collapse navbar-collapse navbar-right">
                <ul class="nav navbar-nav">
                    <li><a href="{{ route("features") }}">{{__('frontmenu.Features')}}</a></li>
                    <li><a href="{{ route("pricing") }}">{{__('frontmenu.Pricing')}}</a></li>
                    <li><a href="{{ route("support") }}">{{__('frontmenu.Support')}}</a></li>
                    <li class="active outline">
                        <a href="{{ module_enabled('Subdomain')?route('front.workspace'):route('login') }}">{{__('frontmenu.Login')}}</a>
                    </li>
                    <li class="active"><a href="{{ route('signup') }}">{{__('frontmenu.SignUp')}}</a></li>
                </ul>
            </div>
        </div><!--/.container-->
    </nav><!--/nav-->

</header><!--/header-->

@yield("content")
<section id="bottom">
    <div class="container" data-wow-duration="1000ms" data-wow-delay="600ms">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <img src="{{$setting->logo_image_url}}" height="50px">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="widget">
                            <p style="padding: 15px 0;">{{ $setting->main_name }} {{__('home.text1Acomplete')}}</p>
                        </div>
                    </div><!--/.col-md-3-->

                    <div class="col-md-6 col-sm-12">
                        <em class="bg-warning">{{__('home.feedback')}}<br/><br/>
                            &horbar; {{__('home.feedbackBy')}}</em>

                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="widget">
                    <h3>{{ $setting->main_name }}</h3>
                    <ul>
                        <li><a href="{{ route("pricing") }}">{{__('frontmenu.Pricing')}}</a></li>
                        <li><a href="{{ route("features") }}">{{__('frontmenu.Features')}}</a></li>
                    </ul>
                </div>
            </div><!--/.col-md-3-->

            <div class="col-md-3 col-sm-6">
                <div class="widget">
                    <h3>{{__('frontmenu.Support')}}</h3>
                    <ul>
                        @foreach($pages as $page)
                            <li><a href="{{ route("page",[$page->slug]) }}">{{$page->title}}</a></li>
                        @endforeach
                        <li><a href="{{ route("support") }}">{{__('frontmenu.ContactUs')}}</a></li>
                    </ul>
                </div>
            </div><!--/.col-md-3-->

        </div>
    </div>
</section><!--/#bottom-->


<script src="{{ asset('assets/site/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/site/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/site/js/main.min.js') }}"></script>
<script src="{{ asset('assets/site/js/wow.min.js') }}"></script>
<script src="{{ asset('assets/site/js/jquery.blockui.min.js') }}"></script>
<script src="{{ asset('assets/site/js/custom.min.js') }}"></script>
@yield("javascript")
</body>
</html>
