@extends('admin.adminlayouts.adminlayout')


@section('mainarea')

    <!-- BEGIN PAGE HEADER-->
    <div class="page-head">
        <div class="page-title"><h1>
                {{ trans('pages.superAdminDashboard.title') }}
            </h1></div>
    </div>
    <div class="page-bar">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <span class="active">{{ trans('pages.superAdminDashboard.title') }}</span>
            </li>
        </ul>

    </div>
    <!-- END PAGE HEADER-->

    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN EXAMPLE TABLE PORTLET-->

            <div class="portlet light bordered">

                <div class="portlet-body">

                    @include('vendor.froiden-envato.update.update_blade')

                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="box-title" id="structure">@lang('core.systemDetails')</h4>
                            @include('vendor.froiden-envato.update.version_info')
                        </div>
                        <div class="col-md-12">
                            <div class="text-center">
                                <a href="https://envato.froid.works/version-log/{{config('froiden_envato.envato_product_name')}}" class="btn btn-lg btn-default btn-outline" target="_blank"><i class="fa fa-external-link"></i> View Change Log</a>
                            </div>

                            @if (!empty($plugins = \Froiden\Envato\Functions\EnvatoUpdate::plugins()))
                                <div class="col-md-12 m-t-20">
                                    <h4>{{strtoupper(config('froiden_envato.envato_product_name'))}} Official Plugins</h4>

                                    <div class="row">
                                        @foreach ($plugins as $item)
                                            <div class="note note-info">
                                                <div class="row">
                                                    <div class="col-xs-2 col-lg-1">
                                                        <a href="{{ $item['product_link'] }}" target="_blank">
                                                            <img src="{{ $item['product_thumbnail'] }}" class="img-responsive" alt="">
                                                        </a>
                                                    </div>
                                                    <div class="col-xs-8 col-lg-5">
                                                        <a href="{{ $item['product_link'] }}" target="_blank" class="font-bold">{{ $item['product_name'] }}</a>

                                                        <p class="font-12">
                                                            {{ $item['summary'] }}
                                                        </p>
                                                    </div>
                                                    <div class="col-xs-2 col-lg-6 text-right">
                                                        <a href="{{ $item['product_link'] }}" target="_blank" class="btn btn-md btn-success"><i class="fa fa-arrow-right text-white"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                </div>
                            @endif

                        </div>



                    </div>

                    <hr>
                    <div class="clearfix"></div>

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->

        </div>
    </div>

    {{--DELETE Model--}}
    @php($envatoUpdateCompanySetting = \Froiden\Envato\Functions\EnvatoUpdate::companySetting())
    <div id="updateModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    @if(!is_null($envatoUpdateCompanySetting->supported_until) && \Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->isPast())
                        Support Expired
                    @else
                        <h4 class="modal-title">{{trans('core.confirmation')}}</h4>
                    @endif

                </div>
                <div class="modal-body" id="info">

                        @if(!is_null($envatoUpdateCompanySetting->supported_until) && \Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->isPast())
                            <div class="note-danger note">
                                Your support has been expired on <b><span id="support-date">{{\Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->format('d M, Y')}}</span></b>
                                <br>
                                Please renew your support to get one-click updates
                            </div>

                        @else
                           <p> Take backup of files and database before updating!</p>
                        @endif


                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal"
                            class="btn dark btn-outline">{{trans('core.btnCancel')}}</button>
                    @if(!is_null($envatoUpdateCompanySetting->supported_until) && \Carbon\Carbon::parse($envatoUpdateCompanySetting->supported_until)->isPast())
                        <a href="{{ config('froiden_envato.envato_product_url') }}" target="_blank"
                           class="btn btn-success btn-small">Renew support <i class="fa fa-shopping-cart"></i></a>
                    @else

                        <button type="button" data-dismiss="modal" id="success" class="btn green"><i class="fa fa-check"></i> Yes, update it!
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{--END DELETE MODAL--}}



@stop

@section('footerjs')
    @include('vendor.froiden-envato.update.update_script')
@stop
