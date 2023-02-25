<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Category;
use App\Models\Restaurants;
use Maatwebsite\Excel\Facades\Excel;


class CategoryController extends Controller
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
		$v_id		= $request->v_id;
		$category	= Category::where('user_id',$v_id)->get();
		return view('category.index',compact('category'),compact('v_id'));
	} 

	public function create(Request $request)
	{
		$category	= [];
		// $v_id		= $request->v_id;
		$store		= Restaurants::approved()->get();
		return view('category.form',compact('category',/*'v_id',*/'store'));
	}

	public function edit(Request $request, $id)
	{
		$id			= $request->id;
		$category	= Category::find($id);
		$store		= Restaurants::approved()->get();
		$v_id		= $request->v_id;
		return view('category.form',compact('category','v_id','store'));
	}

	public function update(Request $request)
	{
		if ($request->id > 0) {
			$category	= Category::find($request->id);
		} else {
			$category	= new Category;
		}
		$category->name	= $request->name;
		$slug		= \Str::slug($request->name,'_');
		if ($slug == '') {
			$slug	= str_replace(' ', '_', $request->name);
		}
		$category->slug		= $slug;  
		$category->status	= $request->status;
		$category->user_id	= \Auth::user()->id;
		$category->save();
		Flash::success('Category details saved successfully.');
		return redirect(getRoleName().'/common/category');
	}

	public function destroy($id)
	{
		$result =  Category::find($id);
		if ($result) {
			$result = $result->delete();
			if ($result)  {
				Flash::success('Category detail is deleted.');
			}
		} else {
			Flash::success('Please Refresh Your Page...');
		}
		return redirect(getRoleName().'/common/category');
	}
}