<?php

namespace App\Http\Controllers;
use App\Exports\TagExport;
use App\Exports\UnitExport;
use App\Exports\BudgetExport;
use App\Exports\CategoryExport;
use App\Exports\ExploreExport;
use App\Exports\AddonExport;
use App\Exports\MenuitemExport;
use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Commondatas;
use App\Models\Cuisines;
use App\Models\Category;
use App\Models\Addon;
use App\Models\Menuitems as Menuitem;
use App\Models\Time;
use App\Models\Explore;
use App\Models\Timeslotcategory;
use App\Models\Timeslotmanagement;
use App\Models\Locations;
use App\Models\Offtimelog;
use App\Models\LogActivity;
use App\Models\Contact;
use App\Models\Restaurants;
use App\Models\BlogCategory;
use Maatwebsite\Excel\Facades\Excel;

class CommonController extends Controller{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$type=\Request::segment(3);
		$common=Commondatas::$type()->get();
		return view('common.index',compact('common'),compact('type'));
	}

	public function show(Request $request)
	{
		$type		= \Request::segment(3);
		$pCount		= 10;
		$innerpage	= ($request->query('innerpage')) ? $request->query('innerpage') : '0';
		$page		= (($request->query('page')) ? $request->query('page')-1 : 0)*$pCount;
		$v_id		= \Auth::user()->id;

		if ($type == 'cuisines') {
			return \Redirect::to(getRoleName().'/cuisines');
			/*$search		= $request->query('search') ? $request->query('search') : '';
			$status		= $request->query('status') ? $request->query('status') : '';
			$cuisines	= Cuisines::where(function($query) use ($search, $status) {
				if ($search != '') {
					// $query->where('name', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%');
					$query->where(function($cqy) {
						$cqy->orWhere('name', 'like', '%'.request()->search.'%')->orWhere('description', 'like', '%'.request()->search.'%');
					});
				}
				if($status != '') {
					$query->where('status',$status );
				}
			})->paginate(10);
			$status	= Locations::all();
			return view('cuisines.index',compact('cuisines','page','status'));*/
		} elseif($type == 'category') {
			// $category	= Category::paginate(10);
			$search		= $request->query('search') ? $request->query('search') : '';
			$status		= $request->query('status') ? $request->query('status') : '';
			$category	= Category::where(function($query) use ($search, $status) {
				if ($search != '') {
					$query->where('name', 'like', '%'.$search.'%');
				}
				if ($status != '') {
					$query->where('status',$status );
				}
			})->paginate(10);
			// print_r(json_encode( $category ));die;
			// $status	= Locations::all();
			return view('category.index',compact('category','page','status'));
		} elseif($type == 'addon' || $type == 'unit') {
			$search	= $request->query('search') ? $request->query('search') : '';
			$status	= $request->query('status') ? $request->query('status') : '';
			$addon	= Addon::where(function($query) use ($search, $status) {
			if ($search != '') {
				$query->where('name','like','%'.$search.'%')->orWhere('price','like','%'.$search.'%');
				}
				if($status != '') {
					$query->where('status',$status );
				}
			});
			$addon  = $addon->where('user_id',$v_id)->where('type',$type)->paginate($pCount, ['*'], 'innerpage', $innerpage);
			
			return view('addon.index',compact('addon','type','v_id','innerpage','pCount','page'));
		} elseif($type == 'menu_item') {
			$search    = $request->query('search') ? $request->query('search') : '';
			$status    = $request->query('status') ? $request->query('status') : '';
			$stockstatus    = $request->query('stock_status') ? $request->query('stock_status') : '';
			$menuitem  = Menuitem::where(function($query) use ($search, $status,  $stockstatus) {
			   if ($search != '') {
				$query->where('name', 'like', '%'.$search.'%')->orWhere('price','like','%'.$search.'%')->orWhere('quantity','like','%'.$search.'%');
				} 
				if($status != '') {
					$query->where('status',$status );
				}
				if($stockstatus != '') {
					$query->where('stock_status',$stockstatus );
				}
			});
			$menuitem           =  $menuitem->where('vendor_id',$v_id)->paginate($pCount, ['*'], 'innerpage', $innerpage);
			$menuCat    = Restaurants::with('allCategories')->where('vendor_id',$v_id)->first();
			return view('menuitem.index',compact('menuitem','v_id','innerpage','pCount','menuCat'));
		} elseif($type == 'timeslotcategory') {
			//$timeslotcategory   = Timeslotcategory::paginate(10);
			 $search    = $request->query('search') ? $request->query('search') : '';
			$status    = $request->query('status') ? $request->query('status') : '';
			$timeslotcategory=Timeslotcategory::where(function($query) use ($search, $status, $type) {
				
			if ($search != '') {
			$query->where('name', 'like', '%'.$search.'%');
			}
			if($status != '') {
			$query->where('status',$status );
			}
			})->paginate(10);
			$status   = Locations::all(); 
			return view('timeslotcategory.index',compact('timeslotcategory','page','status'));
		} elseif($type == 'timeslotmanagement') {
			//$timeslotmanagement = Timeslotmanagement::paginate(10);
			$category           = Timeslotcategory::where('status','active')->get();
			$time_check         = Time::all();
			$status    = $request->query('status') ? $request->query('status') : '';
			$timeslotmanagement=Timeslotmanagement::where(function($query) use ($status, $type) {
				
			
			if($status != '') {
			$query->where('status',$status );
			}
			})->paginate(10);
			$status   = Locations::all(); 
			return view('timeslotmanagement.index',compact('timeslotmanagement'),compact('time_check' , 'category','page','status'));
		} elseif($type == 'explore') {
			//$explore    = Explore::paginate(10);
			$tags       = Commondatas::where('type','tag')->where('status','active')->get();
			$search    = $request->query('search') ? $request->query('search') : '';
			$status    = $request->query('status') ? $request->query('status') : '';
			$explore=Explore::where(function($query) use ($search, $status, $type) {
				
			if ($search != '') {
			$query->where('name', 'like', '%'.$search.'%');
			}
			if($status != '') {
			$query->where('status',$status );
			}
			})->paginate(10);
			$status   = Locations::all(); 
			return view('explore.index',compact('explore' ,'tags','page'));
		} else if($type == 'blogtag'){	
			//$common     = Commondatas::$type()->paginate(10);//print_r($common);exit();
			$search    = $request->query('search') ? $request->query('search') : '';
			$status    = $request->query('status') ? $request->query('status') : '';
			$common=Commondatas::where(function($query) use ($search, $status, $type) {
				$query->where('type',$type);
			if ($search != '') {
			$query->where('name', 'like', '%'.$search.'%');
			}
			if($status != '') {
			$query->where('status',$status );
			}
			})->paginate(10);
			$status   = Locations::all(); 
			return view('common.index',compact('common'),compact('type','page'));           
		} elseif($type == 'blog_category') {
			//$category           = Category::paginate(10);
			$search    = $request->query('search') ? $request->query('search') : '';
			$status    = $request->query('status') ? $request->query('status') : '';
			$category=BlogCategory::where(function($query) use ($search, $status, $type) {
				
			if ($search != '') {
			$query->where('name', 'like', '%'.$search.'%');
			}
			if($status != '') {
			$query->where('status',$status );
			}
			})->paginate(10);// print_r(json_encode( $unit));die;
			$status   = Locations::all();
			return view('category.blog_cat_index',compact('category','page'));
		} elseif ($type == 'budget') {
			//$common     = Commondatas::$type()->paginate(10);//print_r($common);exit();
			$search    = $request->query('search') ? $request->query('search') : '';
			$status    = $request->query('status') ? $request->query('status') : '';
			$common=Commondatas::where(function($query) use ($search, $status, $type) {
				$query->where('type',$type);
				if ($search != '') {
					$query->where('name', 'like', '%'.$search.'%');
				}
				if($status != '') {
					$query->where('status',$status );
				}
			})->paginate(10);
			$status   = Locations::all(); 
			return view('common.index',compact('common'),compact('type','page'));           
		}
	}

	public function update(Request $request)
	{
		if($request->c_id>0){
			$common=Commondatas::find($request->c_id);
		}else{
			$common=new Commondatas;   
			$common->created_by=\Auth::user()->id;
		}
		$common->name=$request->c_name;
		$common->status=$request->c_status;
		$common->type=$request->type;
		$common->updated_by=\Auth::user()->id;
		$common->save();

		Flash::success(ucfirst($request->type).' details saved successfully.');
		return redirect(getRoleName().'/common/'.$request->type);
	}

	public function destroy($id,$type)
	{
		$result =  Commondatas::find($id);
		if($result){
			$result = $result->delete();
			if ($result) {
				Flash::success('deleted successfully.');
			}
		}else{
			Flash::success('Please Refresh Your Page...');
		}
		return redirect(getRoleName().'/common/'.$type);
	}

	public function tagexport(Request $request,$slug)
	{
		$request->all();
		$exporter = app()->makeWith(TagExport::class, compact('request'));  
		return $exporter->download('TagExport_'.date('Y-m-d').'.'.$slug);
	}

	public function unitexport(Request $request,$slug)
	{
		$request->all();
		$exporter = app()->makeWith(UnitExport::class, compact('request'));  
		return $exporter->download('UnitExport_'.date('Y-m-d').'.'.$slug);
	}

	public function budgetexport(Request $request,$slug)
	{
	   $request->all();
		$exporter = app()->makeWith(BudgetExport::class, compact('request'));  
		return $exporter->download('BudgetExport_'.date('Y-m-d').'.'.$slug);
	}

	public function categoryexport(Request $request,$slug)
	{
		$request->all();
		$exporter = app()->makeWith(CategoryExport::class, compact('request'));  
		return $exporter->download('CategoryExport_'.date('Y-m-d').'.'.$slug);
	}

	public function exploreexport(Request $request,$slug)
	{
	   $request->all();
		$exporter = app()->makeWith(ExploreExport::class, compact('request'));  
		return $exporter->download('ExploreExport_'.date('Y-m-d').'.'.$slug);
	}

	public function addonexport(Request $request,$slug)
	{
		$request->all();        $exporter = app()->makeWith(AddonExport::class, compact('request'));  
		return $exporter->download('AddonExport_'.date('Y-m-d').'.'.$slug);
	}

	public function menuitemexport(Request $request,$slug)
	{
		$request->all();
		$exporter = app()->makeWith(MenuitemExport::class, compact('request'));  
		return $exporter->download('MenuitemExport_'.date('Y-m-d').'.'.$slug);
	}

	public function contactpage(Request $request)
	{
		
		$path       = app_path() . "/Models";
		$module     = getModels($path);
		$pageCount      = 10;
		$page           = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		$search     = $request->query('search');
		$filter     = $request->query('filter');
		$contactpage    = new Contact;
		$customerData   = Contact::get();
		// $contactpage    = $contactpage->where('before_change', '!=', '')->where('after_change', '!=', '')->orderBy('id','desc');
		// if ($request->query('user_id') != '') {
		//     $contactpage    = $contactpage->where('user_id',$request->query('user_id'));
		// }
		// if ($request->query('date') != '') {
		//     $sDate  = explode(" - ",$request->date);
		//     $contactpage    = $contactpage->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
		// } 
		// if ($request->query('search') != ''){
		//     $contactpage    = $contactpage->Where('record', 'like', '%'.$search.'%')->orWhere('before_change', 'like', '%'.$search.'%')->orWhere('after_change', 'like', '%'.$search.'%');
		// }
		// if ($request->query('filter') != ''){
		//     $logactivity    = $logactivity->Where('record_id', 'like', '%'.$filter.'%');
		// }
		// if ($request->query('module') != '') {
		//     $contactpage    = $contactpage->where('module',$request->query('module'));
		// }
		$contactpage = $contactpage->paginate($pageCount);
		return view('contactpage.index',compact('contactpage','page','customerData','module'));
	}
}