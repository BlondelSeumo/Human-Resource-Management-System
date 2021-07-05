@extends('admin.adminlayouts.adminlayout')

@section('mainarea')

    <!-- BEGIN PAGE HEADER-->
    <div class="page-head">
        <div class="page-title"><h1>
                {{$pageTitle}}
            </h1></div>
    </div>
    <div class="page-bar">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a onclick="loadView('{{route('admin.dashboard.index')}}')">{{trans('core.home')}}</a>
                <i class="fa fa-circle"></i>
            </li>

            <li>
                <span class="active"> {{trans('core.settings')}}</span>
            </li>
        </ul>
    </div>
    <div class="row">

        <div class="col-md-12">

            <!-- BEGIN EXAMPLE TABLE PORTLET-->

            <div id="load">

                {{--INLCUDE ERROR MESSAGE BOX--}}

                {{--END ERROR MESSAGE BOX--}}


            </div>
            <div class="portlet light bordered">
                <div class="table-toolbar">
                    <div class="row ">
                        <div class="col-md-6">

                            <a href="{{ route('admin.custom-modules.create') }}" class="btn green"><i class="fa fa-refresh"></i> Install/Update Module</a>

                        </div>

                    </div>
                </div>
                <div class="portlet-body form">

                    <div class="row">

                        <div class="col-md-12 ">

                            <ul class="list-group m-t-20" id="files-list">
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <strong>@lang('core.name')</strong>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <strong>Envato Purchase code</strong>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <strong>@lang('core.currentVersion')</strong>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <strong>@lang('core.status')</strong>
                                        </div>
                                    </div>
                                </li>
                                @php
                                    $count = 0;
                                @endphp
                                @forelse ($allModules as $key=>$module)

                                    <li class="list-group-item" id="file-{{ $count++ }}">
                                        <div class="row">
                                            <div class="col-md-2">
                                                {{ $key }}
                                            </div>
                                            <div class="col-md-4 text-right">
                                                @if(in_array($module, $hrmPlugins))

                                                    @if (config(strtolower($module).'.setting'))
                                                        @php
                                                            $settingInstance = config(strtolower($module).'.setting');

                                                            $fetchSetting = $settingInstance::first();
                                                        @endphp

                                                        @if (config(strtolower($module).'.verification_required'))
                                                            {!! $fetchSetting->purchase_code ?? '<a href="javascript:;" class="btn btn-success btn-sm btn-outline verify-module" data-module="'. strtolower($module).'" >'.__('core.verifyEnvato').'</a>' !!}
                                                        @endif
                                                    @endif


                                                @endif


                                            </div>
                                            <div class="col-md-3 text-right">
                                                @if (config(strtolower($module).'.setting'))
                                                    <label
                                                        class="label label-info">{{ File::get($module->getPath() . '/version.txt') }}</label>
                                                @endif
                                            </div>

                                            <div class="col-md-3 text-right">
                                                <input type="checkbox" name="status"
                                                       @if(in_array($module, $hrmPlugins)) checked
                                                       @endif class="make-switch change-module-setting"
                                                       data-size="small" data-module-name="{{ $module }}"/>
                                            </div>

                                        </div>
                                    </li>
                                @empty
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i
                                                        class="icon-layers"></i>
                                                </div>
                                                <div class="title m-b-15">@lang('messages.noModules')
                                                </div>
                                                <div class="subtitle">
                                                    <a href="{{ route('admin.custom-modules.create') }}"
                                                       class="btn btn-success btn-sm btn-outline"><i
                                                            class="fa fa-refresh"></i>Install/Update Module</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse

                            </ul>
                        </div>


                        @include('vendor.froiden-envato.update.plugins')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
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

@endsection

@section('footerjs')
    {!! HTML::script("assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")!!}
    <script>


        $('.change-module-setting').on('switchChange.bootstrapSwitch', function (event, state) {
            var module = $(this).data('module-name');

            if ($(this).is(':checked'))
                var moduleStatus = 'active';
            else
                var moduleStatus = 'inactive';

            var url = '{{route('admin.custom-modules.update', ':module')}}';
            url = url.replace(':module', module);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {'id': module, 'status': moduleStatus, '_method': 'PUT', '_token': '{{ csrf_token() }}'}
            })
        });

        $('.verify-module').click(function () {
            var module = $(this).data('module');
            var url = '{{route('admin.custom-modules.show', ':module')}}';
            url = url.replace(':module', module);
            $('#modelHeading').html('Verify your purchase');
            $.ajaxModal('#projectCategoryModal', url);
        })
    </script>

@endsection
