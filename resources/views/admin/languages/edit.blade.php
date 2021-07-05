<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><strong><i class="fa fa-edit"></i> {{trans('core.btnEditLanguage')}}</strong></h4>
    </div>
    {!! Form::open(['class'=>'form-horizontal ajax_form','method'=>'POST','id'=>'edit_form']) !!}
    <div class="modal-body" id="edit-modal-body">
        <div class="portlet-body form">

            <div id="error_edit"></div>
            <div class="form-body">

                <div class="form-group">
                    <label class="col-md-4 control-label">Locale: <span class="required">
                                    * </span>
                    </label>

                    <div class="col-md-8">
                        <input type="text" class="form-control" name="locale" id="locale"
                               placeholder="" value="{{$language->locale}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Language: <span class="required">
                                    * </span>
                    </label>

                    <div class="col-md-8">
                        <input type="text" class="form-control" name="language" id="language"
                               placeholder="Language" value="{{$language->language}}">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Status: <span class="required">
                                    * </span>
                    </label>

                    <div class="col-md-8">
                        <select name="active" id="active" class="form-control">
                            <option value="1">{{__('core.active')}}</option>
                            <option value="0" @if($language->active==0)selected @endif>{{__('core.inactive')}}</option>
                        </select>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <div class="modal-footer">
        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    <button type="button" id="submitbutton_edit" onclick="updateSubmit({{$language->id}});return false;"
                            class=" btn green"><i class="fa fa-edit"></i> {{trans('core.btnSubmit')}}</button>

                </div>
            </div>
        </div>
    </div>
{!!  Form::close()  !!}            <!-- END EXAMPLE TABLE PORTLET-->
</div>

