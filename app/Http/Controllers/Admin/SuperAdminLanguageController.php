<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Admin\Language\UpdateRequest;
use App\Http\Requests\Admin\Language\EditRequest;
use App\Http\Requests\Admin\Language\StoreRequest;
use App\Models\Admin;


use App\Models\Employee;
use App\Models\Language;
use App\Models\Pages;
use Barryvdh\TranslationManager\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

use Yajra\DataTables\Facades\DataTables;

class SuperAdminLanguageController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'Language';
        $this->settingActive = 'active';
        $this->languageActive = 'active';
        $this->langPath = base_path() . '/resources/lang';

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
        return View::make('admin.languages.index', $this->data);
    }

    // DATA TABLE ajax request
    public function ajax_languages()
    {
        $result = Language::select('id', 'locale', 'language','active')
            ->get();

        return DataTables::of($result)
            ->editColumn('active', function ($row) {
                $color = ['1' => 'success', '0' => 'danger'];

                return "<span class='label label-{$color[$row->active]}'>" .
                    trans("core." . $row->active==1?'active':'inactive') . "</span>";
            })
            ->addColumn('edit', function ($row) {


                $string='';
                if($row->locale !=='en'){
                    $string = '<a style="width: 75px;" class="btn purple btn-sm margin-bottom-10"  href="javascript:;" onclick="showEdit(' . $row->id . ');return false;" >
										          <i class="fa fa-edit"></i> ' . trans('core.edit') . '</a>';
                    $string .='</a>
							<a class="btn red btn-sm margin-bottom-5" href="javascript:;" onclick="del('.$row->id.')"><i class="fa fa-trash"></i><span class="hidden-sm hidden-xs"> ' .
                        trans("core.btnDelete") . '</span></a>';
                }
                return $string;
            })
            ->rawColumns(['edit','active'])
            ->make();
    }


    public function create()
    {
        return View::make('admin.languages.create');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $language = new Language();
        $row = $this->updateData($request, $language, 'create');

        Pages::insertData($row);

        return Reply::success('messages.langSuccess');
    }


    public function edit(EditRequest $request, $id)
    {
        $language = Language::find($id);
        return View::make('admin.languages.edit', compact('language'));
    }

    public function update(UpdateRequest $request, $id)
    {

        $language = Language::findOrFail($id);
        $this->updateData($request, $language, 'update');
        return Reply::success('messages.langSuccess');
    }

    private function updateData($request, $language, $type)
    {
        $dir = $this->langPath . '/' . str_slug(strtolower($request->locale));


        if ($type == 'update') {
            $oldLangExists = File::exists($this->langPath . '/' . strtolower($language->locale));
            if($oldLangExists) {
                File::move($this->langPath . '/' . strtolower($language->locale), $dir);
            }
        }
        // check and create lang folder
        $langExists = File::exists($dir);
        if (!$langExists) {
            File::makeDirectory($dir);
            // update lang folder name
            File::copyDirectory($this->langPath . '/en', $dir);

            Translation::where('locale', 'en')->get()->map(function ($translation) {
                $translation->delete();
            });
        }

        $language->locale = str_slug(strtolower($request->locale));
        $language->language = $request->language;
        $language->active = $request->active;
        $language->save();
        return $language;
    }

    public function destroy(Request $request,$id)
    {

        Language::destroy($id);

        return Reply::success("messages.successDelete");
    }
}
