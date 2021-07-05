@extends('admin.adminlayouts.adminlayout')



@section('head')
<link rel="stylesheet" href="{{ asset('assets/global/plugins/dropzone-master/dist/dropzone.css') }}">
<style>
    .file-bg {
        height: 150px;
        overflow: hidden;
        position: relative;
    }

    .file-bg .overlay-file-box {
        opacity: .9;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        text-align: center;
    }
</style>
@endsection

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
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript: loadView('{{route('admin.custom-modules.index')}}')"  class="btn btn-warning btn-sm btn-outline"><i class="fa fa-arrow-left"></i> @lang('core.back')</a>

                            <div class="white-box">
                                <h3 class="box-title m-b-0">@lang("core.customModule")</h3>

                                <p class="text-danger m-b-10 font-13">
                                    @lang("messages.loginAgain")
                                </p>

                                <div class="row">

                                    <div class="col-md-12 m-t-20">
                                        <h4 class="box-title text-info">Step 1</h4>
                                        <form action="{{ route('admin.update-settings.store') }}" class="dropzone" id="file-upload-dropzone">
                                            {{ csrf_field() }}

                                            <div class="fallback">
                                                <input name="file" type="file" multiple />
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-12 m-t-20" id="install-process">

                                    </div>

                                    <div class="col-md-12 m-t-20">
                                        <h4 class="box-title text-info">Step 2</h4>
                                        <h4 class="box-title">@lang('messages.moduleFile')</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <ul class="list-group" id="files-list">
                                            @foreach (\Illuminate\Support\Facades\File::files($updateFilePath) as $key=>$filename)
                                                @if (\Illuminate\Support\Facades\File::basename($filename) != "modules_statuses.json" && strpos(\Illuminate\Support\Facades\File::basename($filename) , 'auto') === false)
                                                    <li class="list-group-item" id="file-{{ $key+1 }}">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                {{ \Illuminate\Support\Facades\File::basename($filename) }}
                                                            </div>
                                                            <div class="col-md-4">
                                                                Uploaded On:
                                                                {{ \Carbon\Carbon::parse(\Illuminate\Support\Facades\File::lastModified($filename))->format('jS F, Y g:i a')}}
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <button type="button" class="btn btn-success btn-sm btn-outline install-files" data-file-no="{{ $key+1 }}" data-file-path="{{ $filename }}">@lang('core.install') <i class="fa fa-refresh"></i></button>

                                                                <button type="button" class="btn btn-danger btn-sm btn-outline delete-files" data-file-no="{{ $key+1 }}" data-file-path="{{ $filename }}">@lang('core.delete') <i class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>



                                </div>
                                <!--/row-->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.common.delete')
@endsection

@section('footerjs')
    <script src="{{ asset('assets/global/plugins/dropzone-master/dist/dropzone.js') }}"></script>
<script type="text/javascript">
    Dropzone.options.fileUploadDropzone = {
        paramName: "file",
        dictDefaultMessage: "Upload or drop the downloaded file here",
        accept: function (file, done) {

            done();
        },
        init: function () {
            this.on("success", function (file, response) {
                var viewName = $('#view').val();
                if(viewName == 'list') {
                    $('#files-list-panel ul.list-group').html(response.html);
                } else {
                    $('#thumbnail').empty();
                    $(response.html).hide().appendTo("#thumbnail").fadeIn(500);
                }
                window.location.reload();
            })
        }
    };

    var updateAreaDiv = $('#update-area');
    var refreshPercent = 0;
    var checkInstall = true;

    function checkIfFileExtracted(){
        $.easyAjax({
            type: 'GET',
            url: '{!! route("admin.updateVersion.checkIfFileExtracted") !!}',
            success: function (response) {
                checkInstall = false;
                $('#download-progress').append("<br><i><span class='text-success'>Installed successfully. Reload page to see the changes.</span>.</i>");
                window.location.reload();
            }
        });
    }

    $('.install-files').click(function(){
            $('#install-process').html('<div class="alert alert-info ">Installing...Please wait (This may take few minutes.)</div>');

            let filePath = $(this).data('file-path');
            $.easyAjax({
                type: 'POST',
                url: '{!! route("admin.custom-modules.store") !!}',
                data: {"_token": "{{ csrf_token() }}", filePath: filePath},
                success: function (response) {
                    if(response.status === 'success'){
                        $('#download-progress').append("<br><i><span class='text-success'>Installed successfully. Reload page to see the changes.</span>.</i>");
                        window.location.reload();
                    }
                    $('#install-process').html('');
                }
            });
        });

        $('.delete-files').click(function(){
            let filePath = $(this).data('file-path');
            let fileNumber = $(this).data('file-no');

            $('#deleteModal').modal('show');
            $("#deleteModal").find('#info').html('You will not be able to recover the deleted file!?');
            $('#deleteModal').find("#delete").off().click(function () {
                $.easyAjax({
                    type: 'POST',
                    url: '{!! route("admin.update-settings.deleteFile") !!}',
                    data: {"_token": "{{ csrf_token() }}", filePath: filePath},
                    success: function (response) {
                        $('#file-'+fileNumber).remove();
                    }
                });
            })
        });
</script>
@endsection
