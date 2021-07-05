<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">{{trans('core.btnAddLanguage')}}</h4>
    </div>
    <div class="modal-body">
        <div class="form">

            <!-- BEGIN FORM-->
            {!! Form::open(array('class'=>'form-horizontal ajax_form','id'=>'add_form')) !!}

            <div id="error"></div>
            <div class="form-body">

                <div class="form-group">
                    <label class="col-md-4 control-label">Locale: <span class="required">
                                    * </span>
                    </label>

                    <div class="col-md-8">
                        <input type="text" class="form-control" name="locale" id="locale"
                               placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Language: <span class="required">
                                    * </span>
                    </label>

                    <div class="col-md-8">
                        <input type="text" class="form-control" name="language" id="language"
                               placeholder="Language">
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
                            <option value="0">{{__('core.inactive')}}</option>
                        </select>
                    </div>
                </div>

            </div>


            <!-- END FORM-->
        </div>
    </div>

    <div class="modal-footer">
        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" id="submitbutton_add" onclick="addAdminSubmit();return false;"
                            class=" btn green">{{trans('core.btnSubmit')}}
                    </button>

                </div>
            </div>
        </div>
        {!!  Form::close()  !!}
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
</div>
