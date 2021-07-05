<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Reply;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Pages\UpdateRequest;


use App\Models\Pages;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

use Yajra\DataTables\Facades\DataTables;

class PagesController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'Pages';
        $this->settingActive = 'active';
        $this->pagesActive = 'active';

        $this->middleware(function ($request, $next) {
            if (admin()->type != 'superadmin') {
                echo View::make('admin.errors.noaccess', $this->data);
                die();
            }
            return $next($request);
        });
    }

    public function index()
    {
        return View::make('admin.pages.index', $this->data);
    }

    // DATA TABLE ajax request
    public function ajax_pages()
    {
        $result = Pages::select('id', 'title', 'description', 'created_at')
            ->get();

        return DataTables::of($result)
            ->editColumn('created_at', function ($row) {
                return date('d-M-Y', strtotime($row->created_at));
            })->addColumn('edit', function ($row) {

                $string = '<a style="width: 75px;" class="btn purple btn-sm margin-bottom-10"  href="javascript:;" onclick="showEdit(' . $row->id . ');return false;" >
										          <i class="fa fa-edit"></i> ' . trans('core.edit') . '</a>';

                return $string;
            })
            ->rawColumns(['title', 'description', 'edit'])
            ->make();
    }


    public function edit($id)
    {
        $this->page = Pages::find($id);

        if ($this->page == null) {
            return View::make('admin.errors.noaccess', $this->data);
        }

        return View::make('admin.pages.edit', $this->data);
    }

    /**
     * Update the specified emailtemplate in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $email = Pages::findOrFail($id);

        $email->title = request()->get('title');
        $email->description = request()->get('description');
        $email->slug = str_slug(strtolower(request()->get('title')));
        $email->save();
        return Reply::success('messages.updateSuccess');
    }


}
