@extends('admin.adminlayouts.adminlayout')

@section('head')
{!! HTML::style('assets/admin/pages/css/pricing.css')!!}

@stop


@section('mainarea')


        <!-- BEGIN PAGE HEADER-->
<div class="page-head"><div class="page-title"><h1>
            @lang("pages.billing.plans")
        </h1></div></div>
<div class="page-bar">
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a onclick="loadView('{{route('admin.dashboard.index')}}')">{{trans('core.dashboard')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span class="active">@lang("pages.billing.plans")</span>
        </li>

    </ul>

</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="page-content-inner">
            <div class="portlet light portlet-fit ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-green"></i>
                        <span class="caption-subject font-green bold uppercase">Monthly Plans</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="pricing-content-1">
                        <div class="row">
                            @foreach($plans as $plan)
                                <div class="col-md-3">
                                <div class="price-column-container border-active">
                                    <div class="price-table-head bg-blue">
                                        <h2 class="no-margin">{{ $plan->plan_name }}</h2>
                                    </div>
                                    <div class="arrow-down border-top-blue"></div>
                                    <div class="price-table-pricing">
                                        <h3>
                                            <sup class="price-sign">{{ $setting->currency_symbol }}</sup>{{ $plan->monthly_price }}</h3>
                                        <p>per month</p>
                                        @if($loggedAdmin->company->subscriptionPlan && $loggedAdmin->company->subscriptionPlan->id == $plan->id && $loggedAdmin->company->package_type == 'monthly' && $loggedAdmin->company->license_expired != 1)
                                            <div class="price-ribbon">Selected</div>
                                        @endif
                                    </div>
                                    <div class="price-table-content">
                                        <div class="row mobile-padding">
                                            <div class="col-xs-3 text-right mobile-padding">
                                                <i class="icon-user"></i>
                                            </div>
                                            <div class="col-xs-9 text-left mobile-padding">{{ $plan->start_user_count }} - {{ $plan->end_user_count }} Users</div>
                                        </div>
                                    </div>
                                    <div class="arrow-down arrow-grey"></div>
                                    @if(round($plan->monthly_price) > 0 && ($setting->stripe_status == 1 || $setting->paypal_status == 1))
                                        <div class="price-table-footer">
                                            @if($loggedAdmin->company->subscriptionPlan && $loggedAdmin->company->subscriptionPlan->id == $plan->id && $loggedAdmin->company->package_type == 'monthly' && $loggedAdmin->company->license_expired != 1)
                                                -
                                            @else
                                                <button type="button" data-package-id="{{ $plan->id }}" data-package-type="monthly" class="btn grey-salsa btn-outline sbold uppercase price-button selectPackage">Subscribe</button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="price-table-footer">
                                            -
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="page-content-inner">
            <div class="portlet light portlet-fit ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-green"></i>
                        <span class="caption-subject font-green bold uppercase">Yearly Plans</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="pricing-content-1">
                        <div class="row">
                            @foreach($plans as $plan)
                                <div class="col-md-3">
                                    <div class="price-column-container border-active">
                                        <div class="price-table-head bg-blue">
                                            <h2 class="no-margin">{{ $plan->plan_name }}</h2>
                                        </div>
                                        <div class="arrow-down border-top-blue"></div>
                                        <div class="price-table-pricing">
                                            <h3>
                                                <sup class="price-sign">{{ $setting->currency_symbol }}</sup>{{ $plan->annual_price }}</h3>
                                            <p>per year</p>
                                            @if($loggedAdmin->company->subscriptionPlan && $loggedAdmin->company->subscriptionPlan->id == $plan->id && $loggedAdmin->company->package_type == 'annual' && $loggedAdmin->company->license_expired != 1)
                                                <div class="price-ribbon">Selected</div>
                                            @endif
                                        </div>
                                        <div class="price-table-content">
                                            <div class="row mobile-padding">
                                                <div class="col-xs-3 text-right mobile-padding">
                                                    <i class="icon-user"></i>
                                                </div>
                                                <div class="col-xs-9 text-left mobile-padding">{{ $plan->start_user_count }} - {{ $plan->end_user_count }} Users</div>
                                            </div>
                                        </div>
                                        <div class="arrow-down arrow-grey"></div>
                                        <div class="price-table-footer">
                                            @if(round($plan->annual_price) > 0 && ($setting->stripe_status == 1 || $setting->paypal_status == 1))
                                                <div class="price-table-footer">
                                                    @if($loggedAdmin->company->subscriptionPlan && $loggedAdmin->company->subscriptionPlan->id == $plan->id && $loggedAdmin->company->package_type == 'annual' && $loggedAdmin->company->license_expired != 1)
                                                        -
                                                    @else
                                                        <button type="button" data-package-id="{{ $plan->id }}" data-package-type="annual" class="btn grey-salsa btn-outline sbold uppercase price-button selectPackage">Subscribe</button>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="price-table-footer">
                                                    -
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="package-select-form" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                    </div>
                    <div class="modal-body">
                        Loading...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn blue">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{--Ajax Modal Ends--}}

@stop



@section('footerjs')

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {!! HTML::script('assets/global/plugins/uniform/jquery.uniform.min.js')!!}
    {!! HTML::script("assets/global/plugins/datatables/datatables.min.js")!!}
    {!! HTML::script("assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js") !!}
    {!! HTML::script("assets/global/plugins/datatables/plugins/responsive/dataTables.responsive.js")!!}
    {!! HTML::script("assets/global/plugins/datatables/plugins/responsive/responsive.bootstrap.js")!!}
    {!! HTML::script("assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")!!}
    {!! HTML::script("assets/global/plugins/select2/js/select2.js")!!}
    {!! HTML::script('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')!!}
    {!! HTML::script('assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js')!!}
    {!! HTML::script('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js')!!}
    <script src="https://js.stripe.com/v3/" type="text/javascript"></script>

<script>
    // Show Create Holiday Modal
    $('body').on('click', '.selectPackage', function(){
        var id = $(this).data('package-id');
        var type = $(this).data('package-type');
        var url = "{{ route('admin.billing.select-package',':id') }}?type="+type;
        url = url.replace(':id', id);
        $.ajaxModal('#package-select-form', url);
    });
</script>


@stop
