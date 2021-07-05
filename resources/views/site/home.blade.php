@extends("site.app")
@section("title")
    {{__('home.title')}}
@endsection

@section("css")
    <style type="text/css">
        .videoWrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 */
            padding-top: 25px;
            height: 0;
        }

        .videoWrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
@endsection
@section("content")

    <section id="main-slider" class="no-margin">
        <div class="carousel slide">
            <div class="carousel-inner">
                <div class="item active"
                     style="background-image: url({{ asset('assets/site/images/slider/dashboard_home_1600.jpg') }});">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1"
                                        style="text-shadow: 0 3px 6px rgba(0,0,0,0.2)">
                                        {{__('home.text1Acomplete')}}</h1>
                                    <h2 class="animation animated-item-2"
                                        style="text-shadow: 0 3px 6px rgba(0,0,0,0.2); margin: 18px 0; font-weight: 400;">{{__('home.trustText')}} {{ $setting->main_name }}</h2>
                                    <form id="sign-up-form-1" method="post"
                                          class="form-inline animation animated-item-3" action="{{ route("signup") }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                        <div class="form-group">
                                            <input type="text" name="email" class="form-control input-lg"
                                                   required="required" placeholder="{{__('home.YourEmailID')}}"/>
                                        </div>
                                        <input type="submit" name="submit" class="input-lg btn btn-primary"
                                               value="Try for FREE!"/>
                                    </form>
                                    <h2 class="animation animated-item-2"{{__('home.noCreditCard')}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.item-->
            </div><!--/.carousel-inner-->
        </div><!--/.carousel-->
    </section><!--/#main-slider-->

    <section id="feature">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>{{__('home.whyWillYou')}} <i class="fa fa-heart"></i> {{ $setting->main_name }}</h2>
            </div>

            <div class="row">
                <div class="features">
                    <div class="col-md-6 col-sm-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                        <div class="feature-wrap">
                            <i class="fa fa-{{ strtolower($setting->currency) }}"></i>
                            <h2>{{__('home.PayFixedMonthly')}}</h2>
                            <h3>{{__('home.PayFixedMonthlyDescription')}}</h3>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-6 col-sm-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                        <div class="feature-wrap">
                            <i class="fa fa-thumbs-o-up"></i>
                            <h2>{{__('home.EasyToUse')}}</h2>
                            <h3>{{__('home.EasyToUseDescription')}}</h3>

                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-6 col-sm-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                        <div class="feature-wrap">
                            <i class="fa fa-mobile"></i>
                            <h2>{{__('home.MobileFriendly')}}</h2>
                            <h3>{{__('home.MobileFriendlyDescription')}}</h3>
                        </div>
                    </div><!--/.col-md-4-->


                    <div class="col-md-6 col-sm-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                        <div class="feature-wrap">
                            <i class="fa fa-rocket"></i>
                            <h2>{{__('home.EverImproving')}}</h2>
                            <h3>{{__('home.EverImprovingDescription')}}</h3>
                        </div>
                    </div><!--/.col-md-4-->
                </div><!--/.services-->
            </div><!--/.row-->
        </div><!--/.container-->
    </section><!--/#feature-->



    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="center">
                        <h2>{{__('home.GetStartedToday')}}</h2>
                        <p class="lead">{!!  __('home.signUpAndStart')!!}
                        </p>
                        <form id="sign-up-form-1" method="post" class="form-inline" action="{{ route("signup") }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control input-lg" required="required"
                                       placeholder="{{__('home.YourEmailID')}}"/>
                            </div>
                            <input type="submit" name="submit" class="input-lg btn btn-primary" value="{{__('home.signUp')}}"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <img class="img-responsive" src="{{ asset('assets/site/images/dashboard_bottom_1600_1.jpg') }}" width="100%"/>
@endsection
