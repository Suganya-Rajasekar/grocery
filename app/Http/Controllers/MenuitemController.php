<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Menuitems as Menuitem;
use App\Exports\MenuitemExport;
use App\Models\Addon;
use App\Models\Themes;
use App\Models\Preferences;
use App\Models\Category;
use App\Models\Restaurants;
use Illuminate\Support\Facades\Storage;
use File;
use DB;
use App\Imports\MenuitemImport;
use Maatwebsite\Excel\Facades\Excel;

class MenuitemController extends Controller
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
		$pCount     = 10;
		$page 		= ($request->page) ? $request->page : 0;
		$innerpage  = ($request->query('innerpage')) ? $request->query('innerpage') : 0;
		$search     = ($request->query('search')) ? $request->query('search') : '';
		$v_id       = $request->v_id;
		$menuitem   = Menuitem::with('categories')->where('vendor_id',$v_id);
		if($search != ''){
			$menuitem = $menuitem->where(function($query) use($search){
				if(is_numeric($search)) {
					$query->where('quantity',$search);
				} else {
					$query->where('name',$search);	
				}
			});
		}
		// $menuCat    = clone ($menuitem);
		// $menuCat    = $menuCat->get();
		$menuCat    = Restaurants::with('allCategories')->where('vendor_id',$v_id)->first();
		$menuitem   = $menuitem->paginate($pCount, ['*'], 'innerpage', $innerpage);
		return view('menuitem.index',compact('menuitem','menuCat','v_id','innerpage','pCount','page'));
	}

	public function create(Request $request)
	{
		$menuitem   = 'new';
		$v_id       = $request->v_id;
		$restaurants    = Restaurants::where('vendor_id',$v_id)->first();
		$addon          = Addon::where('user_id',$v_id)->where('type','addon')->where('status','active')->get();
		$unit           = Addon::/*where('user_id',$v_id)->*/where('type','unit')->where('status','active')->get();
	    $category        = Category::where('status','active')->get();
	    $themes = $preferences = collect();
	    if($restaurants->vendor_info->home_event == 'yes') {
	    	$themes 	 = Themes::where('status','active')->get();
	    	$preferences = Preferences::where('status','active')->get();
	    }
		return view('menuitem.form',compact('menuitem','addon','category','unit','restaurants','preferences','themes'),compact('v_id'));
	}

	public function edit(Request $request, $v_id, $id)
	{
		$menuitem   = Menuitem::where('vendor_id',$v_id)->where('id',$id)->first();
		if(empty($menuitem)) {
			return redirect()->back();
		}
		$menuitem->unit_det = $menuitem->unit_detail;
		$addon      = Addon::where('user_id',$v_id)->where('type','addon')->where('status','active')->get();
		$unit       = Addon::where('type','unit')->where('status','active')->get();
		$category   = Category::where('status','active')->get();
		$restaurants= Restaurants::where('vendor_id',$v_id)->first();
		$themes = $preferences = collect();
	    if($restaurants->vendor_info->home_event == 'yes') {
	    	$themes 	 = Themes::where('status','active')->get();
	    	$preferences = Preferences::where('status','active')->get();
	    }
		return view('menuitem.form',compact('menuitem','addon','category','unit','restaurants','preferences','themes'),compact('v_id'));
	}

	Public function orderrearrange(Request $request)
	{
		if ($request->v_id =='') {
			$v_id	= \Auth::user()->id;
		} else{
			$v_id	= $request->v_id;
		}
		$pCount		= 10;
		$innerpage  = ($request->query('innerpage')) ? $request->query('innerpage') : 0;
		$restaurants= Restaurants::where('vendor_id',$v_id)->get();
		$category=$restaurants[0]['category_order'];
		if(!empty($category)){
			$menuitem   = Menuitem::with('categories')->has('categories')->where('vendor_id',$v_id)->groupBy('main_category')->orderByRaw("FIELD(main_category,$category)")->get();
        } else{
		     $menuitem   = Menuitem::with('categories')->has('categories')->where('vendor_id',$v_id)->groupBy('main_category')->get();
		}
         return view('orderrearrange.form',compact('menuitem','v_id','innerpage','pCount'));
	}

	public function store(Request $request)
	{
	    $category = $request->input('category');
        $res_id = $request->input('restaurant_id');
        $data=implode(',',$category);
        $Restaurant = Restaurants::find($res_id);
        $Restaurant->category_order = $data;
        $Restaurant->save();
        Flash::success('menuitems details saved successfully.');
        return \Redirect::back();
	}

	public function update(Request $request)
	{
		$page = ($request->page) ? $request->page : '';
		$rules['name']        = ['required', 'string', 'max:255'];
		$rules['price']       = ['required'];
		$rules['description'] = ['required'/*, 'string', 'min:90', 'max:255'*/];
		$rules['main_category.*'] = 'required|exists:categories,id';
		if ($request->res_type != 'event') {
			// $rules['tag_type']    = 'required';
			if ($request->id) {
				$menu = Menuitem::where('id',$request->id)->with(['restaurant' => function($query){
					$query->select('id','preparation_time');	
				}])->orderBy('id','ASC')->first();	
				if ($menu && $menu->restaurant->preparation_time == 'preorder') {
					$rules['preparation_time']       = ['required','in:tomorrow'];				
				} elseif ($menu && $menu->restaurant->preparation_time == 'ondemand') {
					$prep_time = 'in:'.$menu->preparation_time;
					$rules['preparation_time']       = ['required',$prep_time];				
				}
			} elseif (is_null($request->id)) {
				$prep_time = 'in:1_to_2hrs,2_to_3hrs,tomorrow'; 
				$firstmenuitem = Menuitem::select('id','restaurant_id','preparation_time')->where('vendor_id',$request->v_id)->with(['restaurant' => function($query){
					$query->select('id','preparation_time');	
				}])->orderBy('id','ASC')->first();
				if ($firstmenuitem && $firstmenuitem->restaurant->preparation_time == 'preorder') {
					$rules['preparation_time']       = ['required','in:tomorrow'];
				} elseif ($firstmenuitem && $firstmenuitem->restaurant->preparation_time == 'ondemand') {
					$prep_time  = 'in:'.$firstmenuitem->preparation_time;
					$rules['preparation_time']       = ['required',$prep_time];				
				}
			}
		} 
		if ($request->res_type == "home_event") {
			$rules['themes']	  = 'required';
			$rules['preferences'] = 'required';
			$rules['chef_meal']	  = 'required'; 
 		}
		if ( $request->hasFile('image') ) {
			$rules['image']     = 'required';/*|array|dimensions:max_width=1024,max_height=1024*/
			$rules['image.*']   = 'mimes:jpeg,jpg,png';
		}

		$response = $this->validateDatas($request->all(),$rules,[],[],'web');
		if (!empty($response)) {
			Flash::error($response['message']);
			$request->flash();
			return \Redirect::back()->withErrors($response['validator'])->withInput();
		}
		if ($request->id > 0) {
			$menuitem   = Menuitem::find($request->id);
		} else {
			$menuitem   = new Menuitem;
		}
		$restaurant     = Restaurants::where('vendor_id',$request->v_id)->first();
		$menuitem->restaurant_id 		= $restaurant->id;
		$menuitem->unit          		= $request->only(['unit','price_unit']);
		$menuitem->name          		= $request->name;
		$menuitem->description   		= $request->description;
		$menuitem->price         		= $request->price;
		$menuitem->discount      		= ($request->res_type != 'event' && $restaurant->home_event != 'yes') ? $request->discount : '' ;
		$menuitem->purchase_quantity   	= ($request->res_type != 'event' && $restaurant->home_event != 'yes') ?  $request->purchase_quantity : '';
		$menuitem->item_type     		= ($request->res_type != 'event' && $restaurant->home_event != 'yes') ? $request->item_type : '';
		$slug                    		= \Str::slug($request->name,'_');
		if ($slug == '') {
			$slug   = str_replace(' ', '_', $request->name);
		}
		$menuitem->slug             = $slug;
		$menuitem->preparation_time = ($restaurant->preparation_time == 'tomorrow') ? 'tomorrow' : (($request->preparation_time != '') ? $request->preparation_time : '2_to_3hrs');
		$menuitem->vendor_id        = $request->v_id;
		if ($request->main_category != '') {
			$menuitem->main_category = implode(",", $request->main_category);
		}
		if ($request->addons!='') {
			$menuitem->addons   = implode(",", $request->addons);
		} else {
			$menuitem->addons   = '';
		}
		$menuitem->stock_status = ($request->res_type != 'event' && $restaurant->home_event != 'yes') ? $request->stock_status : '';
		$mquantity = 0;
		$mquantity = ($request->stock_status == 'instock') ? $request->quantity : 0;
		// $mstock    = ($request->stock_status == 'instock') ? $request->stock : 0;
		$menuitem->quantity = $mquantity;
		// $menuitem->stock    = $mstock;
		$menuitem->tag_type = ($request->res_type != 'event' && !empty($request->tag_type) && $restaurant->home_event != 'yes') ? implode(',',$request->tag_type) : ''; 
		$menuitem->food_type     = ($request->res_type != 'event' && $restaurant->home_event != 'yes') ? 'menuitem' : (($restaurant->home_event == 'yes') ? 'home_event_menu' : 'ticket');
		if (getRoleName() == 'admin') {
			$menuitem->status   = $request->status;
		}
		$files  = '';
		if ($request->file('image')) {
			if ($request->image[0]!='') {
				$dir    = 'public/menu_items/'.$request->v_id.'/';
				$directory  = base_path().'/storage/app/public/menuitems/'.$request->v_id.'/';
				if (!(\File::exists($directory))) {
					$destinationPath = \File::makeDirectory($directory, 0777, true);
				}
				$destinationPath= $directory;
				foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name ){
					$org_name   = $_FILES['image']['name'][$key];
					$ext        = pathinfo($org_name, PATHINFO_EXTENSION);
					$file_name  = time()."-".rand(10,100).$key.'.'.$ext;
					$file_tmp   = $_FILES['image']['tmp_name'][$key];
					if ($file_name !='') {
						/*$avatar_path        = $request->file('image')->storeAs('public/menuitems/'.$request->v_id, $file_name);*/
						$upload = move_uploaded_file($file_tmp,$destinationPath.$file_name);
						$files .= /*$dir.*/$file_name.',';
						// $compress = \SiteHelpers::urlimg_compress($destinationPath.$file_name);
					}
				}
			}
			if (rtrim($files,',')!='') {
				$menuitem->image = rtrim($files,',');
			} else {
				$menuitem->image = $files;
			}
		}
		$menuitem->reason = isset($request->reason) ? $request->reason : '';
		$category	= $menuitem->main_category;
		$cat		= new_category($restaurant->id,$menuitem->main_category);
		if ($cat) {
			if (!empty($restaurant->category_order)) {
				$data	= explode(',',$restaurant->category_order);
				array_push($data,$category);
				$data	= implode(",", $data);
				$restaurant->category_order = $data;
				$restaurant->save();
			}
		}
	   	$menuitem->minimum_order_quantity = (isset($request->minimum_quantity)) ? $request->minimum_quantity : '';
		$menuitem->themes		= (isset($request->themes)) ? implode(',',$request->themes) : '';
		$menuitem->preferences	= (isset($request->preferences)) ? implode(',',$request->preferences) : '';
		$menuitem->meal			=  (isset($request->chef_meal)) ? implode(',',$request->chef_meal) : '';
		$menuitem->save();

		\Flash::success('Menu Item details saved successfully.');
		if (getRoleName() == 'admin') {
			return redirect(getRoleName().'/chef/'.$request->v_id.'/menu_item/edit/'.$menuitem->id.'?from=web&page='.$page);
		} else {
			return redirect(getRoleName().'/common/menu_item');
		}
	}

	public function menuitemexport(Request $request,$id,$slug)
	{
		$request->merge(['slug'=>$slug]);
		$request->all();
		$exporter = app()->makeWith(MenuitemExport::class, compact('request'));  
		return $exporter->download('MenuitemExport_'.date('Y-m-d').'.'.$slug);
	}

	public  function menuitemimport(Request $request)
	{
		
		$validate =  $request->validate([
			'file' => 'required|mimes:xls,csv,txt|max:2048'
		]);

		
		try {
			\Excel::import(new MenuitemImport, $request->file('file')->store('temp'));
		} 
		catch (\Maatwebsite\Excel\Validators\ValidationException $e)
		{
			$failures = $e->failures();
			foreach ($failures as $failure) 
			{  
				$row = $failure->row();
				$wrongrow = $row;
				$updatedrow = $wrongrow-1;
				$error = $failure->errors(); 
			}
			
			return redirect()->back()->with('error','Upto '.$updatedrow.' row is updated. From Row '.$wrongrow.' errors appears '. $error[0].' please fix errors and upload further rows'); 
		}
		
		return redirect()->back()->with('message', 'Your file uploaded to database'); 
	}
}