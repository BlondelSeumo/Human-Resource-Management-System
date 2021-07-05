{!! Form::open(array('url'=>"",'class'=>'form-horizontal ','method'=>'POST','id'=>'edit_form')) !!}

<div id="error_edit"></div>
<div class="form-body">

    <div class="form-group">
        <label class="col-md-2 control-label">Title: <span class="required">
					* </span>
        </label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                   value="{{$page->title}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label">Description: <span class="required">
					* </span>
        </label>
        <div class="col-md-8">
            <textarea id="description" class="form-control" name="description">{{$page->description}}</textarea>
        </div>
    </div>


</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" id="submitbutton_edit" onclick="updateData({{$page->id}});return false;"
                    class=" btn green"><i class="fa fa-edit"></i> {{trans('core.btnSubmit')}}</button>

        </div>
    </div>
</div>
{!!  Form::close()  !!}
