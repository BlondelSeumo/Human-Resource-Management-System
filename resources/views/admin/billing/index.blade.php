@extends('admin.adminlayouts.adminlayout')

@section('head')
<!-- BEGIN PAGE LEVEL STYLES -->
{!! HTML::style('assets/global/plugins/uniform/css/uniform.default.min.css')!!}
{!! HTML::style("assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css") !!}
{!! HTML::style("assets/global/plugins/datatables/plugins/responsive/responsive.bootstrap.css")!!}
{!! HTML::style("assets/global/plugins/select2/css/select2.css")!!}
{!! HTML::style("assets/global/plugins/select2/css/select2-bootstrap.min.css")!!}
{!! HTML::style('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css')!!}
        <!-- END PAGE LEVEL STYLES -->

@stop


@section('mainarea')


        <!-- BEGIN PAGE HEADER-->
<div class="page-head"><div class="page-title"><h1>
            @lang("pages.billing.indexTitle")
        </h1></div></div>
<div class="page-bar">
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a onclick="loadView('{{route('admin.dashboard.index')}}')">{{trans('core.dashboard')}}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span class="active">@lang("pages.billing.indexTitle")</span>
        </li>

    </ul>

</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        @if((!empty($setting->stripe_key) && !empty($setting->stripe_secret) && !empty($setting->stripe_webhook_secret) && ($setting->stripe_status == 1)) ||  (!empty($setting->paypal_client_id) && !empty($setting->paypal_secret) && ($setting->paypal_status == 1)))
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="fa fa-key font-dark"></i> Current Plan
                    </div>
                    <div class="tools">
                        <div class="btn-group">
                            @if(!is_null($firstInvoice) && $firstInvoice->payment_method == 'stripe')
                                @if(!is_null($subscription) && $subscription->ends_at == null)
                                    <button type="button" class="btn btn-danger unsubscription margin-right-10" data-type="stripe" title="Unsubscribe Plan"><i class="fa fa-ban display-small"></i> <span class="display-big">@lang('core.unsubscribe')</span></button>
                                @endif
                            @elseif(!is_null($firstInvoice) && $setting->paypal_client_id != null && $setting->paypal_secret != null && $firstInvoice->payment_method == 'paypal' && $firstInvoice->end_on == null)
                                <button type="button" class="btn btn-danger waves-effect waves-light unsubscription margin-right-10" data-type="paypal" title="Unsubscribe Plan"><i class="fa fa-ban display-small"></i> <span class="display-big">@lang('core.unsubscribe')</span></button>
                            @else

                            @endif

                            <a class="btn green" data-toggle="modal" onclick="loadView('{{route('admin.billing.change_plan')}}')">
                                {{ trans('core.changePlan') }}
                                <i class="fa fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6 text-right"><h4>Plan Name </h4></div>
                            <div class="col-md-6"><h4 class="text-success">{{ $loggedAdmin->company->subscriptionPlan->plan_name }}</h4></div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6 text-right"><h4>Users </h4></div>
                            <div class="col-md-6"><h4 class="text-success">{{ $loggedAdmin->company->subscriptionPlan->start_user_count }} - {{ $loggedAdmin->company->subscriptionPlan->end_user_count }}</h4></div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6 text-right"><h4>Monthly Price </h4></div>
                            <div class="col-md-6"><h4 class="text-success">{{ $loggedAdmin->company->subscriptionPlan->monthly_price }}</h4></div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6 text-right"><h4>Annual Price </h4></div>
                            <div class="col-md-6"><h4 class="text-success">{{ $loggedAdmin->company->subscriptionPlan->annual_price }}</h4></div>
                        </div>
                    </div>

                </div>

            </div>
        @else
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="note note-danger"><i class="fa fa-close"></i> Administrator have not set payment methods. Please contact to administrator.
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        @endif
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div id="load"></div>
        <div class="portlet light bordered">
            <div class="portlet-body">
                @can("create", new \App\Models\Invoice())
                <div class="table-toolbar">
                    <div class="row ">
                        <div class="col-md-12">
                            <button class="btn btn-primary" id="createInvoiceButton" onclick="createInvoice()" >
                                @lang("core.btnCreateInvoice")
                                <i class="fa fa-plus"></i> </button>
                        </div>
                    </div>
                </div>
                @endcan
                <table class="table table-striped table-bordered table-hover responsive" id="admins">
                    <thead>
                    <tr>
                        <th>#</th>
{{--                        <th>Number</th>--}}
                        <th>Plan</th>
                        <th>Amount ($)</th>
                        <th>Date</th>
                        <th>Next Payment Date</th>
                        <th>Payment via</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoices as $key => $invoice)
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td> {{ $invoice->plan->plan_name }} </td>
                            <td> {{ round($invoice->amount) }} </td>
                            <td> {{ \Carbon\Carbon::parse($invoice->pay_date)->toFormattedDateString() }} </td>
                            <td> {{ \Carbon\Carbon::parse($invoice->next_pay_date)->toFormattedDateString() }} </td>
                            <td class="uppercase"> {{ $invoice->payment_method }} </td>
                            <td> <a target="_blank" href="{{ route('admin.billing.change_plan') }}" class="btn btn-primary btn-circle waves-effect" data-toggle="tooltip" data-original-title="Pay"><span></span> <i class="fa fa-dollar"></i></a>  </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center"> No invoice found </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->

    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal"
     aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add New Invoice</h4>
            </div>
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal"
     aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Invoice</h4>
            </div>
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
        {{--DELETE Model--}}
        <div id="deleteModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">{{trans('core.confirmation')}}</h4>
                    </div>
                    <div class="modal-body" id="info">
                        <p>
                            {{--Confirm Message Here from Javascript--}}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal"
                                class="btn dark btn-outline">{{trans('core.btnCancel')}}</button>
                        <button type="button" data-dismiss="modal" class="btn red" id="delete"><i
                                class="fa fa-trash"></i> Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        {{--END DELETE MODAL--}}

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
        <!-- END PAGE LEVEL PLUGINS -->

<script>

    @if ($payment == "success")
        showToastrMessage("@lang("messages.invoicePaymentSuccess")", "@lang("core.success")", "success");
    @elseif($payment == "fail")
        showToastrMessage("@lang("messages.invoicePaymentFail")", "@lang("core.error")", "error");
    @elseif($payment == "cancel")
        showToastrMessage("@lang("messages.invoicePaymentCancel")", "@lang("core.error")", "error");
    @endif
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $.fn.select2.defaults.set("theme", "bootstrap");

    $("#createModal").on('hidden.bs.modal', function () {
        $(this).find('.modal-body').html('Loading...');
        $(this).data('bs.modal', null);
    });


    $('body').on('click', '.unsubscription', function(){
        var type = $(this).data('type');
        $('#deleteModal').modal('show');

        $("#deleteModal").find('#info').html('Are you sure ! You want to unsubscribe this plan?');

        $('#deleteModal').find("#delete").off().click(function () {

            var url = "{{ route('admin.billing.unsubscribe') }}";
            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data:  {'_token': token, '_method': 'POST', 'type': type},
                container: "#deleteModal",
                success: function (response) {
                    if (response.status === "success") {
                        $('#deleteModal').modal('hide');
                        table.fnDraw();
                    }
                }
            });

        });
    });


    function createInvoice() {
        var url = "{{ route("admin.billing.create") }}";

        $("#createModal").removeData('bs.modal').modal({
            remote: url,
            show: true
        });

        $("#createModal").on('loaded.bs.modal', function() {
            prepareModal();
        });

        $("#createModal").on('hidden.bs.modal', function () {
            $(this).find('.modal-body').html('Loading...');
            $(this).data('bs.modal', null);
        });
    }

    function showEdit(id) {
        var url = "{{ route("admin.billing.edit", '#id') }}";
        url = url.replace("#id", id);

        $("#editModal").removeData('bs.modal').modal({
            remote: url,
            show: true
        });

        $("#editModal").on('loaded.bs.modal', function() {
            prepareModal();
        });

        $("#editModal").on('hidden.bs.modal', function () {
            $(this).find('.modal-body').html('Loading...');
            $(this).data('bs.modal', null);
        });
    }
</script>
{{--INLCUDE ERROR MESSAGE BOX--}}

{{--END ERROR MESSAGE BOX--}}
@stop
