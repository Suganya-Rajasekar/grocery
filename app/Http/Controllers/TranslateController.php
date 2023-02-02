<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTranslateRequest;
use App\Http\Requests\UpdateTranslateRequest;
use App\Repositories\TranslateRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Translate;
use App\Models\Language;
use Flash;
use Response;

class TranslateController extends AppBaseController
{
    /** @var  TestimonialsRepository */
    private $TranslateRepository;

    public function __construct(TranslateRepository $TranslateRepository)
    {
        $this->middleware('CheckAdmin');
        $this->TranslateRepository = $TranslateRepository;
    }

    /**
     * Display a listing of the category.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $base_lng = Language::where('base',1)->pluck('id')->first();
        $search['type'] = 1;
        $search['lng']  = $base_lng;
        $translate = $this->TranslateRepository->all($search);

        return view('translate.index')
            ->with('translates', $translate);
    }

        /**
     * Show the form for creating a new category.
     *
     * @return Response
     */
    public function create()
    {
        return view('translate.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateTranslateRequest $request)
    {
        $fields = $request->field;
        foreach ($request->lng as $key => $lng) {
            if($request->type == 2)
            {
                foreach ($fields as $ke => $field_name) {
                    $flight = Translate::updateOrCreate(
                        ['tbl' => $request->tbl, 'lng' => $lng, 'field' => $field_name,'field_fk' => $request->field_fk,'type' => $request->type,'key' => $field_name],
                        ['content' => ($request->$field_name)[$key]]
                    );
                }
            }
            else
            {
                $request->key = strtolower(str_replace(' ','_',$request->key));
                $flight = Translate::updateOrCreate(
                    ['tbl' => $request->tbl, 'lng' => $lng,'type' => $request->type,'key' => $request->key],
                    ['content' => $request->content[$key],'key' => $request->key]
                );
            }
        }
        Flash::success('Translation saved successfully.');
        return redirect(route($request->tbl.'.index'));
    }
}
