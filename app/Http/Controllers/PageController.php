<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flash;
use App\Models\Page;
use App\Models\Customer;
use App\Models\Chefs;
use App\Models\User;
use App\Models\Chefsreq;
use App\Models\Cuisines;
use App\Http\Controllers\Api\AuthController; 
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\PageExport;



class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $pageCount  = 10;
        $page   = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        //$allpage=Page::paginate(10);
        $allpage    =   new Page;
        $search = $request->query('search');
        if ($request->query('search') != ''){ 
            $allpage = $allpage->Where('title', 'like', '%' .$search. '%')->orWhere('slug', 'like', '%' .$search. '%');
        }
        if ($request->query('status') != '') {
            $allpage = $allpage->where('status',$request->query('status'));
        }
        $allpage = $allpage->paginate($pageCount);
        return view('pages.index',compact('allpage','page'));
    }   
     public function show()
    {
       $page=Page::all();
        return view('pages.index',compact('page'));
    } 
    public function create()
    {
       $pages=[];
        return view('pages.form',compact('pages'));
    }
    public function edit(Request $request, $id)
    {
       $pages=Page::find($id);
       if(!$pages){
        $pages=Page::find($id);
       }
        return view('pages.form',compact('pages'));
    }
    public function update(Request $request){
         
        $rules['title']          = ['required', 'string', 'max:255'];
        $rules['slug']          = ['required', 'string', 'max:255'];
        $rules['status']          = ['required'];

        $response = $this->validateDatas($request->all(),$rules,[],[],'web');
        if (!empty($response)) {
            Flash::error($response['message']);
            $request->flash();
            return \Redirect::back()->withErrors($response['validator'])->withInput();
        }

        if($request->c_id>0){
            $pages=Page::find($request->c_id);            
        }else{
            $pages=new Page;            
        }
        $pages->title=$request->title;
        $pages->slug=$request->slug;
        $pages->content=$request->content;
        $pages->status=$request->status;
        $pages->save();

        Flash::success('Page details saved successfully.');
        return redirect(url(getRoleName().'/pages'));
    }
public function destroy($id)
    {
        $result =  Page::find($id);
        if($result){
            $result = $result->delete();
            if ($result) {
                Flash::success('Page detail is deleted.');
            }
        }else{
            Flash::success('Please Refresh Your Page...');
        }
        return redirect(getRoleName().'/pages');

    } 
    public function showpage($slug)
    {
        $page = Page::where('slug', '=', $slug)->first();
        
        return view('page',compact('page'));
    }
    public function pagesexport(Request $request,$slug) 
    {
        $request->all();
        $exporter = app()->makeWith(PageExport::class, compact('request'));  
        return $exporter->download('PagesExport_'.date('Y-m-d').'.'.$slug);
    }
}
