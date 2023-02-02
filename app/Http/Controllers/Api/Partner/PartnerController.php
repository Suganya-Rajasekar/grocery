<?php
namespace App\Http\Controllers\Api\Partner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Addon;
use App\Models\Chefs;
use App\Models\Restaurants;
use App\Models\Menuitems;
use App\Models\Offtimelog;
use App\Models\Orderdetail;
use App\Models\Notifyme;
use App\Models\BlastnotificationLogs;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

use App\Models\UserDocuments;
use File;

/**
 * @author : Suganya
 * @version Feb 2021
 * @return \Illuminate\Http\JsonResponse
 * Partner functionalities
 */

class PartnerController extends Controller
{
	private $status		= 403;
	private $message	= "Only vendors are allowed";

	public function testRole()
	{
		$guard	= (request('from') == 'mobile') ? 'api' : 'web';
		$user	= auth($guard)->user();
		$role_id= User::where('id', $user->id)->pluck('role')->toArray()[0];

		if(isset($role_id) && ($role_id == 3 || $role_id == 1 || $role_id == 5)){
			return true;
		} else {
			return false;
		}
	}

	public function checkVendorRole()
	{
		$guard	= (request('from') == 'mobile') ? 'api' : 'web';
		$user	= auth($guard)->user();
		$role_id= User::where('id', $user->id)->pluck('role')->toArray()[0];

		if(isset($role_id) && ($role_id == 3)){
			return true;
		} else {
			return false;
		}
	}

	/* Chef Info */
	public function vendorData( Request $request)
	{
		$status		= 200;
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$auth_role	= auth($guard)->user()->role;
		$auth_id	= ($auth_role == 1 || $auth_role == 5) ? $request->v_id : auth($guard)->user()->id;
		if ($this->testRole() || $auth_role == 1) {
			
			$cmessage	= $restaurants = array();
			if ($this->method == 'PATCH') {
				$rules['tags']		= 'required|array';
				$rules['tags.*']	= 'required|exist_check:common_datas,where:type:=:tag-whereIn:id:'.implode('~', $request->tags);
				// $rules['budget']	= 'required|numeric|exists:common_datas,id';
				$rules['tax']		= 'numeric';
				$rules['budget']	= 'required|numeric';
				$rules['fssai']		= 'required|numeric';
				$rules['location']	= 'required|numeric|exists:locations,id';
				$rules['address']	= 'required';
				$rules['locality']	= 'required';
				$rules['latitude']	= 'required';
				$rules['longitude']	= 'required';
				$rules['ext_address']		= 'required';
				$rules['description']		= 'required|min:250';
				$rules['preparation_time']	= 'required|in:preorder,ondemand';
			} elseif ($this->method == 'PUT') {
				$rules['mode']	= 'required|in:open,close';
			}

			if ($this->method == 'PATCH' || $this->method == 'PUT') {
				$nicenames['preparation_time'] = 'Preparation time';
				$this->validateDatas($request->all(),$rules,$cmessage,$nicenames);
			}

			$selectArr		= ['id','description', 'tax', 'tags', 'budget', 'location', 'locality', 'landmark', 'preparation_time', 'adrs_line_1 as ext_address','adrs_line_2 as address', 'latitude', 'longitude','mode'];
			$restaurants	= Restaurants::where('vendor_id',$auth_id);
			$restaurants	= ($this->method == 'PUT') ? $restaurants->addSelect('mode','id') : $restaurants->addSelect($selectArr);
			$restaurants	= $restaurants->first();
			// $restaurants Restaurants::find($res->id);
			if ($this->method == 'PATCH') {
				$restaurants->tags			= /*implode(',',*/ $request->tags/*)*/;
				$restaurants->budget		= $request->budget;
				$restaurants->tax			= (isset($request->tax)) ? $request->tax :0;
				$restaurants->location		= $request->location;
				$restaurants->fssai			= $request->fssai;
				$restaurants->locality		= $request->locality;
				$restaurants->landmark		= (isset($request->landmark)) ? $request->landmark :'';
				$restaurants->description	= $request->description;
				$restaurants->adrs_line_2	= $request->address;
				$restaurants->adrs_line_1	= $request->ext_address;
				$restaurants->latitude		= $request->latitude;
				$restaurants->longitude		= $request->longitude;
				$restaurants->preparation_time	= $request->preparation_time;
				$restaurants->preorder			= ($request->preparation_time == 'preorder') ? 'yes' : 'no';

				$restaurants->save();
				$restaurants->makeHidden('preorder', 'adrs_line_2', 'adrs_line_1');
				$message	= 'Secondary info updated successfully';
			} elseif ($this->method == 'PUT') {
				$restaurants->mode		= $request->mode;
				$restaurants->save();
				if($request->mode == "open"){
					$this->notifyme_notification($request->v_id);
				}
				$this->schedule($request);
				$message	= 'Mode updated successfully';
			} else {
				$message	= 'Secondary info details fetched';
			}
			if (!empty($restaurants) > 0) {
				$restaurants->makeHidden('updated_at');
				if ($this->method != 'PUT') 
					$restaurants->budget_name	= $restaurants->location_name = '';
				if (!empty($restaurants->budget_info)) {
					$restaurants->budget_name	= $restaurants->budget_info->name;
				}
				if (!empty($restaurants->location_info)) {
					$restaurants->location_name	= $restaurants->location_info->name;
				}
				$restaurants->makeHidden('location_info','budget_info');
			}
			$response['vendor_secondaryInfo']	= $restaurants;
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	/* Chefs Catgories */
	public function categories( Request $request)
	{
		$status		= 200;
		if ($this->testRole()) {
			$guard		= ($request->from == 'mobile') ? 'api' : 'web';
			$auth_id	= auth($guard)->user()->id;
			$rules		= array('function' => 'required|in:list,save,update');
			if ($request->function == 'save' || $request->function == 'update') {
				$rules['name']	= 'required';
				// $rules['pid']	= 'required|numeric';
			} 
			if ($request->function == 'update') {
				$rules['cid']	= 'required|numeric|exists:categories,id';
				$rules['status']= 'required|in:p_inactive,active';
			}

			$nicenames['cid']	= 'Category Id';
			// $nicenames['pid'] = 'Parent Category Id';
			$nicenames['name']	= 'Name';
			$nicenames['status']= 'Status';
			// validates other request data
			$this->validateDatas($request->all(),$rules,[],$nicenames);

			if ($request->function == 'save' || $request->function == 'update') {
				$slug	= Str::slug($request->name,'_');
				if ($slug == '') {
					$slug	= str_replace(' ', '_', $request->name);
				}
			}

			$categories	= new Category;
			$categories->user_id = $auth_id;
			if ($request->function == 'list') {
				$categories	= $categories->select('id','name','user_id','status')->where('user_id', $auth_id)/*->where('status', 'active')*/->where('p_id', 0);
				if (isset($request->search) && $request->search != '') {
					$categories = $categories->where('name', 'Like', '%'.$request->search.'%');
				}
				$categories = $categories->paginate(20);
				foreach ($categories as $key => $value) {
					$value->menu_count	= count($value->menuitems ?? []);
					$value->makeHidden('menuitems','user_id');
				}
				$response['category']	= $categories;
				$message				= "Category data fetched successfully";
			} elseif ($request->function == 'save') {
				$categories->name	= $request->name;
				// $categories->pid	= $request->pid;
				$categories->slug	= $slug;
				$categories->status	= 'inactive';
				$categories->save();
				$message	= "Category created successfully";
			} else {
				$categories	= $categories::find($request->cid);
				if (($categories->status == 'inactive' || $categories->status == 'declined') && $categories->status != $request->status) {
					$message	= ($categories->status == 'inactive') ? "Category updated successfully but you cannot able to change the status of addon until Admin mark it as an approved one" : "Category does not updated because Admin declined your addon" ;
				} else {
					$message	= "Category updated successfully";
					$categories->status	= $request->status;
				}
				$categories->name	= $request->name;
				$categories->slug	= ($slug != $categories->slug) ? $slug : $categories->slug;
				// $categories->pid	= $request->pid;
				$categories->save();
			}
			$response['user_id']	= $auth_id;
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	/* Chefs Addons */
	public function addons( Request $request)
	{
		$status		= 200;
		if ($this->testRole()) {
			$guard		= ($request->from == 'mobile') ? 'api' : 'web';
			$auth_id	= auth($guard)->user()->id;
			$rules	= array('function' => 'required|in:list,save,update,stockupdate,menu_addons');
			if ($request->function == 'save' || (isset($request->name) && $request->name != null && $request->function == 'update')) {
				$rules['name']	= 'required';
				$rules['price']	= 'required|numeric';
			}
			if ($request->function == 'update') {
				$rules['aid']	= 'required|numeric';
			}
			if ($request->function == 'stockupdate' ) {
				$rules['aid']	= 'required|numeric';
				$rules['status']= 'required|in:p_inactive,active';
			}

			$nicenames['aid']	= 'Addon Id';
			$nicenames['price'] = 'Price';
			$nicenames['name']	= 'Name';
			$nicenames['status']= 'Status';
			// validates other request data
			$this->validateDatas($request->all(),$rules,[],$nicenames);

			if ($request->function == 'save' || (isset($request->name) && $request->name != null && $request->function == 'update')) {
				$slug	= Str::slug($request->name,'_');
				if ($slug == '') {
					$slug	= str_replace(' ', '_', $request->name);
				}
			}

			$addon	= new Addon;
			$addon->user_id = $auth_id;
			if ($request->function == 'menu_addons') {
				$userdata	= User::with('addons')->find($auth_id,['id']);
				$userdata->makeHidden('id', 'role_id');
				$userdata->addons->makeHidden([/*'user_id',*/'slug','content']);
				return \Response::json($userdata);
			} elseif ($request->function == 'list') {
				$addon	= $addon->select('id','name','price','user_id','status')->where('user_id', $auth_id)/*->where('status', 'active')*/;
				if (isset($request->search) && $request->search != '') {
					$addon	= $addon->where('name', 'Like', '%'.$request->search.'%');
				}
				$addon	= $addon->orderByDesc('id')->paginate(20);

				$response['addon']	= $addon;
				$message			= "Addon data fetched successfully";
			} elseif ($request->function == 'save') {
				$addon->name	= $request->name;
				$addon->price	= $request->price;
				$addon->slug	= $slug;
				$addon->status	= 'inactive';
				$addon->save();
				$message	= "Addon created successfully";
			} elseif ($request->function == 'stockupdate') {
				$addon		= $addon::find($request->aid);
				if (($addon->status == 'inactive' || $addon->status == 'declined') && $addon->status != $request->status) {
					$message	= ($addon->status == 'inactive') ? "Addon updated successfully but you cannot able to change the status of addon until Admin mark it as an approved one" : "Addon does not updated because Admin declined your addon" ;
				} else {
					$message	= "Addon updated successfully";
					$addon->status	= $request->status;
					$addon->save();
				}
			} else {
				$message	= "Addon updated successfully";
				$addon		= $addon::find($request->aid);
				if ($request->function == 'save' || isset($request->name) && $request->name != null && $request->function == 'update') {
					$addon->name	= $request->name;
					$addon->slug	= ($slug != $addon->slug) ? $slug : $addon->slug;
				}
				$addon->price	= $request->price;
				$addon->save();
			}
			$response['user_id']	= $auth_id;
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	/* Chefs Menus */
	public function menusData( Request $request)
	{
		$status		= 200;
		if ($this->testRole()) {
			$guard		= ($request->from == 'mobile') ? 'api' : 'web';
			$auth_id	= auth($guard)->user()->id;
			$cmessage	= $rules = $nicenames	= [];
			$restaurant	= Restaurants::where('vendor_id',$auth_id)->first();

			if (empty($restaurant)) {
				$status		= 422;
				$response['message']	= $message;
				$message	= 'Your account is not approved yet.';
				return \Response::json($response,$status);
			} else {
				$restaurantId = $restaurant->id;
			}

			if ($this->method == 'POST') {
				$rules['function']			= 'required|in:save,update';
				$rules['name']				= 'required|min:1|max:20';
				$rules['price']				= 'required|numeric|min:0|not_in:0';
				$rules['description']		= 'required';/*|min:90|max:255*/
				// $rules['preparation_time']	= 'required|in:2_to_3hrs,tomorrow';
				// $chefPreparationTime = (request('preparation_time') == '2_to_3hrs') ? 'ondemand' : 'preorder' ;
				if($request->menu_id) {
					$menu = Menuitems::select('id','preparation_time','restaurant_id')->where('id',$request->menu_id)->with(['restaurant' => function($query){
						$query->select('id','preparation_time');	
					}])->orderBy('id','ASC')->first();
					$rules['preparation_time']       = ['required','in:tomorrow'];				
					if($menu && $menu->restaurant->preparation_time == 'ondemand'){
						$prep_time = 'in:'.$menu->preparation_time;
						$rules['preparation_time']       = ['required',$prep_time];				
					}
				} elseif(is_null($request->menu_id)){
					$prep_time = 'in:1_to_2hrs,2_to_3hrs,tomorrow'; 
					$firstmenuitem = Menuitems::select('id','restaurant_id','preparation_time')->where('vendor_id',$auth_id)->with(['restaurant' => function($query){
						$query->select('id','preparation_time');	
					}])->orderBy('id','ASC')->first();
					$rules['preparation_time']       = ['required','in:tomorrow'];
					if($firstmenuitem && $firstmenuitem->restaurant->preparation_time == 'ondemand'){
						$prep_time  = 'in:'.$firstmenuitem->preparation_time;
						$rules['preparation_time']       = ['required',$prep_time];				
					}
				}
				// $rules['preparation_time']	= 'required|in:2_to_3hrs,tomorrow|exist_check:restaurants,where:vendor_id:=:'.$auth_id.'-where:preparation_time:=:'.$chefPreparationTime;
				$rules['category_id']		= 'required|exists:categories,id';
				// $rules['category_id.*']		= 'required|numeric|exists:categories,id'/*|exist_check:categories,where:user_id:=:'.$auth_id*/;
				$rules['item_type']	= 'required|in:veg,nonveg';
				 $addons            = array_filter($request->addons);
				 if(!empty($addons)) {
					$rules['addons']	= 'required|array';
					$rules['addons.*']	= 'required|exists:addons,id|exist_check:addons,where:user_id:=:'.$auth_id;
				 }
				if(isset($request->variant)) {
					$rules['variant']	  = 'required|array';
					$rules['variant.*.id']= 'required|exists:addons,id|condition_check:addons,where:type:=:unit';
				}
				if ($request->function == 'update')
					$rules['menu_id']	= 'required|numeric|exist_check:menu_items,where:vendor_id:=:'.$auth_id;
				if( $request->hasFile('image') )
					$rules['image']	= 'required|mimes:png,jpeg,jpg|max:5000';
					// $rules['image'] = 'required|array|dimensions:max_width=1024,max_height=1024';
					// $rules['image.*'] = 'mimes:jpeg,jpg,png';
			} elseif ($this->method == 'GET') {
				$category_id	= (isset($request->cat_id) && $request->cat_id != '' && is_numeric($request->cat_id)) ? $request->cat_id : 0;
				if ($category_id != 0) {
					$rules['mPage'] = 'required';
					$mPage			= (isset($request->mPage) && $request->mPage != '' && is_numeric($request->mPage)) ? $request->mPage : 1;
				}
			} elseif ($this->method == 'PUT') {
				$rules['quantity']	= 'required';
			} elseif ($this->method == 'PATCH') {
				$rules['menu']		= 'required|array';
				$rules['menu.*.id']	= 'required|exists:menu_items,id|exist_check:menu_items,where:vendor_id:=:'.$auth_id;
				$rules['menu']			= 'required|array';
				$rules['menu.*.newPrice']	= 'required|min:1';
			}

			if ($this->method == 'GET') {
				$cPage = $mPage	= 1;
				$perPage		= 20;
				$search			= (isset($request->search) && $request->search != '') ? $request->search : '';
				
				$cPage			= (isset($request->cPage) && $request->cPage != '' && is_numeric($request->cPage)) ? $request->cPage : 1;
			}
			$this->validateDatas($request->all(),$rules,$cmessage,$nicenames);

			$menus	= ($request->function != 'save' || $this->method == 'PUT') ? Menuitems::find($request->menu_id) : new Menuitems;
			if ($this->method == 'POST') {
				if ($request->function == 'save' || $request->function == 'update') {
					$slug	= Str::slug($request->name,'_');
					if ($slug == '') {
						$slug	= str_replace(' ', '_', $request->name);
					}
				}
				$menus->addons				= (!empty($addons)) ? implode(',', $request->addons) : '';
				$menus->vendor_id			= $menus->created_by	= $auth_id;
				$menus->name				= $request->name;
				$menus->slug				= ($slug != $menus->slug) ? $slug : $menus->slug;
				$menus->price				= $request->price;
				$menus->description			= $request->description;
				$menus->item_type			= $request->item_type;
				$menus->main_category		= $request->category_id;
				$menus->preparation_time	= $request->preparation_time;
				if(isset($request->variant)) {
					foreach($request->variant as $k => $value) {
						$Unit['unit'][$k] 	 	= $value['id'];
						$Unit['price_unit'][$k] = $value['price'];
					}
					$menus->unit 				= $Unit;
				}
				$menus->save();
				
				if( $request->hasFile('image')) {
					$filenameWithExt	= $request->file('image')->getClientOriginalName();
					$filename			= pathinfo($filenameWithExt, PATHINFO_FILENAME);
					$extension			= $request->file('image')->getClientOriginalExtension();
					$fileNameToStore	= time()."-".rand(10,100).'.'.$extension;
					// Storage::delete($user->avatarpath ?? null);
					$avatar_path		= $request->file('image')->storeAs('public/menuitems/'.$auth_id, $fileNameToStore);
					$menus->image		= $fileNameToStore;
				}
				$menus->restaurant_id	= $restaurant->id;
				$menus->save();
				$message	= ($request->function == 'save') ? 'Menu item added successfully.It will reflect in your profile once admin approved' : 'Menu item updated successfully.';
			} elseif ($this->method == 'GET') {
				$aChefsData = $this->fetchChefMenu($auth_id,$restaurantId,$perPage,'',$cPage,$mPage,$search,$category_id);
				$message	= 'Menu items fetched successfully';
				$response['Menus']	= $aChefsData->getChefRestaurant;
			} elseif ($this->method == 'PUT') {
				$menus->stock_status	= $request->stock_status;
				$menus->quantity		= $request->quantity;
				$menus->save();
				$message	= "Stock status updated successfully";
			} elseif ($this->method == 'PATCH') {
				$request->menu = array_map(function($subarray){
					$subarray['price']	= $subarray['newPrice'];
					unset($subarray['newPrice']);
					// $subarray['status']	= "pending";
					return $subarray;
				},$request->menu);
				Menuitems::upsert($request->menu, ['id'], ['price'/*,'status'*/]);
				$message = 'Price updated successfully'; /* .It will reflect in your profile once admin approved */
			}
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function fetchChefMenu ($auth_id,$restaurantId,$perPage='10',$status='',$cPage='1',$mPage='1',$search='',$category_id='',$menu_id='',$catid='')
	{
		$menUList = 'getFirstRestaurant';
		if (\Auth::check() && $this->checkVendorRole()) {
			$menUList = 'getChefRestaurant';
		}
		$chef	= Chefs::with(['food_items.menuscatogory','singlerestaurant'])->approved()->find($auth_id);
		$aChefsData	= Chefs::where('id',$auth_id)   
			->addSelect('id','avatar','cuisine_type')
			->with($menUList, function ($query) use ($restaurantId, $perPage, $cPage, $mPage, $search, $category_id, $status,$menu_id,$catid,$chef) {
				$query->addSelect('id','vendor_id')
				->with('categories', function ($cquery) use ($restaurantId, $perPage, $cPage, $mPage, $search, $category_id, $status,$menu_id,$catid,$chef) {
					if (!empty($category_id)) {
						$cquery->where('categories.id',$category_id);
					}
					if (!empty($catid)) {
						$cquery->orderByRaw('FIELD(categories.id,'.$catid.') DESC');
					} else {
						if ($chef->singlerestaurant->category_order == '') {
							// $cquery->orderBy('categories.name','ASC');
						}else{
							$catrder	= $chef->singlerestaurant->category_order;
							$cquery->orderByRaw("FIELD(categories.id,$catrder)");
						}
					}
					$cquery->with(['menuitems' => function ($mquery) use ($restaurantId, $perPage, $mPage, $search, $category_id, $status,$menu_id,$chef) {
						$mquery->commonselect()->addSelect('tag_type','food_type','purchase_quantity','minimum_order_quantity'); 
						if (!empty($search)) {
							$mquery->where('name' ,'Like', '%'.$search.'%');
						}
						if ($status != '') {
							$mquery->approved();
							if($chef->type != "event" && $chef->type != "home_event") {
								$mquery->instock();
							}
							// $mquery->where('status','approved')->where('stock_status', 'instock')->where('quantity', '>','0');
						}
						$mquery->where('restaurant_id',$restaurantId);
						if (!empty($menu_id)) {
							$mquery->orderByRaw('FIELD(id,'.$menu_id.') DESC');
						}
						if (!empty($category_id)) {
							// $mquery->where('main_category',$category_id);
							$mquery->paginate($perPage, ['*'], 'menuPage', $mPage);
						} else {
							$sPerPage = (!empty($search)) ? 30 : $perPage ;
							$mquery->simplePaginate($sPerPage);
						}
					}])->paginate($perPage, ['*'], 'catPage', $cPage);
				});
			})
			->first();
		$aChefsData->makeHidden(['id', 'avatar', 'cuisines', 'cuisine_type', 'reviewscount', 'ratings']);
		if (!empty($aChefsData->getFirstRestaurant)) {
			$aChefsData->getFirstRestaurant->makeHidden(['id','vendor_id']);
			$aChefsData->getFirstRestaurant->categories->makeHidden(['pivot']);
		}
		return $aChefsData;
	}

	/* Schedule off time in Advance */
	public function schedule( Request $request)
	{
		$status	= 200;
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$auth_role	= auth($guard)->user()->role;
		$auth_id	= ($auth_role == 1 || $auth_role == 5) ? $request->v_id : auth($guard)->user()->id;
		if ($this->testRole() || $auth_role == 1) {
			$cmessage	= $rules = $nicenames	= [];
			if ($this->method == 'POST') {
				$rules['start_date']= 'required|date_format:Y-m-d|after_or_equal:'.date('Y-m-d');
				$rules['end_date']	= 'required|date_format:Y-m-d|after_or_equal:start_date';
				$rules['start_time']= ['required','date_format:H:i:s'];
				$rules['end_time']	= ['required','date_format:H:i:s'];
				$start_date	= request('start_date');
				$end_date	= request('end_date');
				if (strtotime($start_date) <= strtotime(date('Y-m-d'))) {
					array_push($rules['start_time'], 'after:'.date('H:i:s'));
				}
				if (strtotime($start_date) === strtotime($end_date)) {
					array_push($rules['end_time'], 'different:start_time');
				}
			} elseif ($this->method == 'PUT') {
				$rules['mode']  = 'required|in:open,close';  
			}
			$validateResponse = $this->validateDatas($request->all(),$rules,$cmessage,$nicenames,$guard);
			if ($guard == 'web' && !empty($validateResponse)) {
				return \Response::json($validateResponse, 422);
			}

			if ($this->method == 'POST') {				
				$data['off_from']	= $start_date.' '.request('start_time');
				$data['off_to']		= $end_date.' '.request('end_time');
				$chef	= Chefs::find($auth_id)/*->get()->where('avalability','not_avail')*/;
				$chef->singlerestaurant()->update($data);

				$extra['vendor_id']		= $extra['created_by'] = $chef->id;
				$extra['restaurant_id']	= $chef->singlerestaurant->id;
				$extra['created_by']	= $chef->id;
				$datamerge	= array_merge($data, $extra);
				$offlineData= new Offtimelog;
				foreach ($datamerge as $key => $value) {
					if($key != 'created_by')
						$offlineData	= $offlineData->where($key,$value);
				}
				$offlineData		= $offlineData->first();
				$data['vendor_id']	= $auth_id;
				$offData	= (!empty($offlineData)) ? $offlineData->fill($data)->save() : Offtimelog::create($data);

				$message	= 'Your are set to OFF from '.$start_date.' '.request('start_time').' to '.$end_date.' '.request('end_time');
			}
			elseif($this->method == 'PUT'){
				if($request->mode == 'close'){
					$res     = Restaurants::select('id')->where('vendor_id',$auth_id)->first();
					$offtime = new Offtimelog;
					$offtime->vendor_id 	= $auth_id;
					$offtime->restaurant_id = $res->id;
					$offtime->type      	= 'mode';
					$offtime->off_from  	= date('Y-m-d H:i:s',time());
					$offtime->save();
					$message	= 'Your are set to OFFTIME';   
				} else {
					$offtime = Offtimelog::where('vendor_id',$auth_id)->where('type','mode')->where('off_to',null)->first();
					if(!empty($offtime)){
						$offtime->off_to = date('Y-m-d H:i:s',time()); 
						$offtime->save();
						$message	= 'Your are remove the OFF';   
					}					
				}
			}
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function availabilty( Request $request)
	{
		$status		= 200;
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$auth_role	= auth($guard)->user()->role;
		$auth_id	= ($auth_role == 1 || $auth_role == 5) ? $request->v_id : auth($guard)->user()->id;
		if ($this->testRole() || $auth_role == 1) {
			$cmessage	= $rules = $nicenames	= [];
			if ($this->method == 'POST') {
				$rules['opening_time']	= ['required','date_format:H:i:s'];
				$rules['closing_time']	= ['required','date_format:H:i:s','different:opening_time'];
				// $rules['opening_time2']= ['required','date_format:H:i:s','different:opening_time,closing_time'];
				// $rules['closing_time2']	= ['required','date_format:H:i:s','different:opening_time,closing_time,opening_time2'];
			}
			$validateResponse = $this->validateDatas($request->all(),$rules,$cmessage,$nicenames,$guard);

			if ($guard == 'web' && !empty($validateResponse)) {
				return \Response::json($validateResponse, 422);
			}

			if ($this->method == 'POST') {
				Restaurants::where('vendor_id',$auth_id)->update( $request->only(['opening_time', 'closing_time', 'opening_time2', 'closing_time2']));
				$message	= "Availability Updated";
			}elseif ($this->method == 'GET') {
				$response['data']	= Restaurants::where('vendor_id',$auth_id)->select('opening_time', 'closing_time', 'opening_time2', 'closing_time2')->first()->makeHidden(['budget_name']);
				$message	= "Availability Fetched";
			}
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function workingDays( Request $request)
	{
		$status		= 200;
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$auth_role	= auth($guard)->user()->role;
		$auth_id	= ($auth_role == 1 || $auth_role == 5) ? $request->v_id : auth($guard)->user()->id;
		if ($this->testRole() || $auth_role == 1) {
			$cmessage	= $rules = $nicenames	= [];
			if ($this->method == 'POST') {
				$rules['working_days'] = 'array';
			}
			$validateResponse = $this->validateDatas($request->all(),$rules,$cmessage,$nicenames,$guard);

			if ($guard == 'web' && !empty($validateResponse)) {
				return \Response::json($validateResponse, 422);
			}

			if ($this->method == 'POST') {
				$working_days= $request->working_days;
				$week = array('sunday','monday' ,'tuesday','wednesday' ,'thursday' ,'friday' ,'saturday');
				if($working_days){
				foreach ($week as $dayName) {
				$enabledDays[$dayName] = (in_array($dayName,$working_days)) ? 1 : 0;
				}
			    } else {
				    foreach ($week as $dayName) {
					$enabledDays[$dayName] = 0;
					}	
			    } 
				Restaurants::where('vendor_id',$auth_id)->update(['working_days'=>json_encode($enabledDays)]);
				$message	= "Availability Updated";
			}elseif ($this->method == 'GET') {
				$working_days	= Restaurants::where('vendor_id',$auth_id)->select('working_days')->first()->makeHidden(['budget_name']);
				$response['data']=json_decode($working_days->working_days, true);
				$message	= "Availability Fetched";
			}
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function Sales($auth_id)
	{
		$yesterday	= date('Y-m-d',strtotime("-1 days"));
		$lastweek	= lastWeek();
		$lastMonth	= lastMonth();
		$today		= date('Y-m-d');
		$thisweek	= rangeWeek(date('Y-m-d'));
		$thisMonth	= rangeMonth(date('Y-m-d'));

		$statusarr  = ['accepted_boy','food_ready','rejected_boy','pickup_boy','riding','accepted_res','accepted_admin','completed','reached_location','reached_restaurant'];
		$order		= Orderdetail::where('vendor_id',$auth_id)->whereIn('status',$statusarr);
		$oyestersum	= clone ($order);$oyestercnt	= clone ($order);
		$olatweksum	= clone ($order);$olatwekcnt	= clone ($order);
		$olatmntsum	= clone ($order);$olatmntcnt	= clone ($order);

		$otodaysum	= clone ($order);$otodaycnt		= clone ($order);
		$othsweksum	= clone ($order);$othswekcnt	= clone ($order);
		$othsmntsum	= clone ($order);$othsmntcnt	= clone ($order);

		$oyestersum	= $oyestersum->where('date', $yesterday)->sum('vendor_price');
		$oyestercnt	= $oyestercnt->where('date', $yesterday)->count();
		$olatwekcnt	= $olatwekcnt->whereBetween('date', [$lastweek['start'], $lastweek['end']])->count();
		$olatweksum	= $olatweksum->whereBetween('date', [$lastweek['start'], $lastweek['end']])->sum('vendor_price');
		$olatmntcnt	= $olatmntcnt->whereBetween('date', [$lastMonth['start'], $lastMonth['end']])->count();
		$olatmntsum	= $olatmntsum->whereBetween('date', [$lastMonth['start'], $lastMonth['end']])->sum('vendor_price');

		$otodaysum	= $otodaysum->where('date', $today)->sum('vendor_price');
		$otodaycnt	= $otodaycnt->where('date', $today)->count();
		$othsweksum	= $othsweksum->whereBetween('date', [$thisweek['start'], $thisweek['end']])->sum('vendor_price');
		$othswekcnt	= $othswekcnt->whereBetween('date', [$thisweek['start'], $thisweek['end']])->count();
		$othsmntsum	= $othsmntsum->whereBetween('date', [$thisMonth['start'], $thisMonth['end']])->sum('vendor_price');
		$othsmntcnt	= $othsmntcnt->whereBetween('date', [$thisMonth['start'], $thisMonth['end']])->count();


		$response['previous']['yesterday']	= 'Yesterday: '.date('d M',strtotime($yesterday));
		$response['previous']['lastWeek']	= 'Last Week: '.date('d',strtotime($lastweek['start'])).'-'.date('d',strtotime($lastweek['end'])).' '.date('M',strtotime($lastweek['start']));
		$response['previous']['lastMoth']	= 'Last Month: '.date('d',strtotime($lastMonth['start'])).'-'.date('d',strtotime($lastMonth['end'])).' '.date('M',strtotime($lastMonth['start']));

		$response['thisMonth']['today']		= 'Today: '.date('d M',strtotime($today));
		$response['thisMonth']['thisweek']	= 'This Week: '.date('d',strtotime($thisweek['start'])).'-'.date('d',strtotime($thisweek['end'])).' '.date('M',strtotime($thisweek['start']));
		$response['thisMonth']['thisMonth']	= 'This Month: '.date('d',strtotime($thisMonth['start'])).'-'.date('d',strtotime($thisMonth['end'])).' '.date('M',strtotime($thisMonth['start']));
		$response['previous']['yesterSum']	= number_format($oyestersum,2,'.','');
		$response['previous']['yesterCnt']	= $oyestercnt;
		$response['previous']['latWekSum']	= number_format($olatweksum,2,'.','');
		$response['previous']['latWekCnt']	= $olatwekcnt;
		$response['previous']['latMntSum']	= number_format($olatmntsum,2,'.','');
		$response['previous']['latMntCnt']	= $olatmntcnt;

		$response['thisMonth']['todaySum']	= number_format($otodaysum,2,'.','');
		$response['thisMonth']['todayCnt']	= $otodaycnt;
		$response['thisMonth']['thsWekSum']	= number_format($othsweksum,2,'.','');
		$response['thisMonth']['thsWekCnt']	= $othswekcnt;
		$response['thisMonth']['thsMntSum']	= number_format($othsmntsum,2,'.','');
		$response['thisMonth']['thsMntCnt']	= $othsmntcnt;

		return $response;
	}

	/* Insights */
	public function insights( Request $request)
	{
		$status		= 200;
		$message	= "";
		if ($this->testRole()) {
			$guard		= ($request->from == 'mobile') ? 'api' : 'web';
			$auth_id	= auth($guard)->user()->id;
			$cmessage	= $rules = $nicenames	= [];
			$rules['range']	= ['required','in:today,week,month,custom'];
			if ($request->range == 'custom') {
				$rules['start_date']= ['required','date_format:Y-m-d'];
				$rules['end_date']	= ['required','date_format:Y-m-d'];
			}
			$this->validateDatas($request->all(),$rules,$cmessage,$nicenames);

			$data	= ['completed' => 0,'accepted_res' => 0,'pending' => 0,'rejected_res' => 0];
			$unset	= ['accepted_boy','food_ready','rejected_boy','pickup_boy','riding','reached_location','reached_restaurant'];
			if ($request->range == 'week') {
				$dates	= rangeWeek(date('Y-m-d'));
				$start	= $dates['start'];
				$end	= $dates['end'];
			} elseif ($request->range == 'month') {
				$dates	= rangeMonth(date('Y-m-d'));
				$start	= $dates['start'];
				$end	= $dates['end'];
			} elseif ($request->range == 'custom') {
				$start	= $request->start_date;
				$end	= $request->end_date;
			} else {
				$start	= date('Y-m-d');
				$end	= date('Y-m-d');
			}

			$orderData	= Orderdetail::select('status')->where('vendor_id',$auth_id)->whereBetween('date', [$start, $end])->get();
			$orderData	= $orderData->groupBy('status')->map->count()->toArray();
			$order = $total = $completed = $accepted = $pending = $rejected = 0;
			foreach ($orderData as $key => $value) {
				if (in_array($key, $unset) || ($key == 'accepted_res' || $key == 'accepted_admin')) {
					$order += $value;
					unset($orderData[$key]);
				}
				$total += $value;
			}
			if(!empty($orderData)) {
				$orderData['completed'] += $order;
			}
			$orderData['accepted_res'] = 0;
			$return	= array_merge($data,$orderData);
			if ($return['completed'] > 0 && $total > 0) {
				$completed	= ($return['completed'] / $total) * 100;
			}
			if ($return['accepted_res'] > 0 && $total > 0) {
				$accepted	= ($return['accepted_res'] / $total) * 100;
			}
			if ($return['pending'] > 0 && $total > 0) {
				$pending	= ($return['pending'] / $total) * 100;
			}
			if ($return['rejected_res'] > 0 && $total > 0) {
				$rejected	= ($return['rejected_res'] / $total) * 100;
			}
			$response['dates']		= date('M d, D', strtotime($start)).' - '.date('M d, D', strtotime($end));
			$response['total']		= $total;
			$response['insights']	= $return;
			$response['metrics']	= ['completed' => (float) number_format($completed,2,'.',''), 'accepted' => (float) number_format($accepted,2,'.',''), 'pending' => (float) number_format($pending,2,'.',''), 'rejected' => (float) number_format($rejected,2,'.','')];
		} else {
			$status		= 403;
			$message	= "Only vendors are allowed";
		}
		$response['message']			= $message;
		return \Response::json($response,$status);
	}

	/*Documents Uplaod*/

	public function documents(Request $request)
	{
		$status		= $this->status;
		$message	= $this->message;
		$cmessage	= $rules = $nicenames	= [];

		if (!($this->testRole())) {
			$response['message']	= $message;
			return \Response::json($response,$status);
		}

		$user		= $this->authCheck();
		$userId		= ($user['user']->role != 3) ? $request->current_user_id : $user['userId'];
		$checkPresent	= UserDocuments::where('user_id',$userId)->exists();
		if ($this->method == 'POST') {
			$required	= 'required|';
			if ($checkPresent) {
				$required	= '';
			}

			$rules['on_boarding_form']	= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
			$rules['enrollment_form']	= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
			$rules['pan_card']			= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
			$rules['cancelled_cheque']	= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
			$rules['address_proof']		= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
			$rules['fssai_certificate']	= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
			$rules['aadar_image']		= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
			$rules['aadar_type']	= $required.'in:single,front_back';
			if (isset($rules->aadar_type) && $rules->aadar_type == 'front_back') {	
			$rules['aadar_back']		= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
			}
			$rules['gst_declaration']	= $required.'in:0,1';
			if (isset($request->gst_declaration) && $request->gst_declaration == '1') {
				$rules['gst_certificate']	= $required.'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048';
				$rules['gst_no']			= $required.'min:15|max:15';
			}
			$cmessage['on_boarding_form']	= 'Onboarding Form';
			$cmessage['enrollment_form']	= 'Enrollment Form';
			$cmessage['pan_card']			= 'Pan Card';
			$cmessage['cancelled_cheque']	= 'Cancelled Cheque';
			$cmessage['address_proof']		= 'Address Proof of the Kitchen Premises';
			$cmessage['fssai_certificate']	= 'FSSAI Certificate';
			$cmessage['aadar_image']		= 'Aadhar Card';
			$cmessage['aadar_back']		= 'Aadhar Card';
			$cmessage['gst_declaration']	= 'GST number available';
			$cmessage['gst_certificate']	= (isset($request->gst_declaration) && $request->gst_declaration == '1') ? 'GST Certificate' : 'GST declaration form';
			$cmessage['gst_no']				= 'GST Number';
			$from	= (\Request::get('from') == 'mobile') ? 'api' : 'web';
			$response = $this->validateDatas($request->all(),$rules,$cmessage,$nicenames,$from);
			if ($from == 'web' && !empty($response)) {
				return \Response::json($response, 422);
			}

			if ($checkPresent) {
				$userDocuments = UserDocuments::where('user_id',$userId)->first();
			} else {
				$userDocuments = new UserDocuments;
				$userDocuments->user_id = $userId;
			}
			$mainPathString	= 'user_document/'.$userId.'/';
			$mainPath		= storage_path($mainPathString);
			if (!File::exists($mainPath)) {
			    $dc = File::makeDirectory($mainPath, 0777, true, true);
			}
			$files = array('on_boarding_form','enrollment_form','pan_card','cancelled_cheque','address_proof','fssai_certificate','aadar_image','aadar_back','gst_certificate'
			);
			$filesColumn = array('on_boarding_form','enrollment_form','pan_card','cancelled_cheque','address_proof','fssai_certificate','aadar_image','aadar_back','gst_certificate'
			);
			foreach ($files as $fKey => $fValue) {
				if(isset($request->{$fValue}) && $request->hasFile($fValue)) {
					$file			= $request->file($fValue);
					$extension		= $file->getClientOriginalExtension(); 
					$newfilename	= $fValue.'_'.str_replace('-', '_', $user['user']->user_code).'.'.$extension;
					$uploadSuccess	= $file->move($mainPath, $newfilename);
					if ($uploadSuccess) {
						\Storage::delete($userDocuments->{$fValue} ?? null);
						$userDocuments->{$filesColumn[$fKey]} = $newfilename;
						if (!File::exists($mainPath)) {
						}
					}
				}
			}
			if (isset($request->gst_declaration) && $request->gst_declaration == '1')  {
				$userDocuments->gst_no	= $request->gst_no;
			} else {
				$userDocuments->gst_no	= '';
			}
			$userDocuments->gst_declaration	= $request->gst_declaration;
			$userDocuments->status			= ($request->status == 'approved') ? 'approved' : 'pending' ;
			$userDocuments->aadar_type			= ($request->aadar_type == 'single') ? 'single' : 'front_back' ;	
			$userDocuments->save();
			$userDocuments->makeHidden(['id','user_id','on_boarding_form', 'enrollment_form', 'pan_card', 'cancelled_cheque', 'address_proof', 'fssai_certificate', 'aadar_image', 'gst_certificate','created_at','updated_at']);
			$status  = 200;
			$response['userDocuments']	= $userDocuments;
			$message = "Documents are uploaded successfully.";
		} elseif($this->method == 'GET') {
			try {
				if ($checkPresent) {
					$userDocumentsCurrent	= UserDocuments::where('user_id',$userId)->first();
				} else {
					throw new \Exception("Record not found.");
				}
			} catch(\Exception $e) {
				$userDocuments		= new userDocuments;
				$columns			= $userDocuments->getTableColumns();
				$columns			= array_fill_keys($columns,'');
				$columns['user_id']	= $userId;
				$attributes			= $userDocuments->toArray();
				$allArray			= array_merge($columns,$attributes);

				$userDocumentsCurrent = collect($allArray);				
			}
			$userDocumentsCurrent->makeHidden(['id','user_id','on_boarding_form', 'enrollment_form', 'pan_card', 'cancelled_cheque', 'address_proof', 'fssai_certificate', 'aadar_image','aadar_back', 'gst_certificate','created_at','updated_at']);
			$status  = 200;
			$message = 'Success';
			$response['userDocuments'] = $userDocumentsCurrent;
		} else {
			$message = 'Method not found.';
		}

		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function notifyme_notification($v_id)
	{
		$status = 422; $response['message'] = "Unprocessable entry.";
		$user_notify = Notifyme::where('vendor_id',$v_id)->with(['user' => function($query){
			$query->select('id','mobile_token','email','name');
		},'chef' => function($qry){
			$qry->select('id','name');
		}])->get();
		$notified = false;
		if(count($user_notify) > 0) {
			foreach ($user_notify as $key => $value) {
				if($value->status == 0){
					if($value['user']['email'] != '' && $value['user']['email']) {
						$from 	 = env('MAIL_FROM_ADDRESS');
						$mailid  = $value['user']['email'];
						$data['userData']	= $value['user'];
						$data['content'] = 'Just wanted to inform you that '.$value['chef']['name'].' is ready to serve you food now..You may now place your order on the app and enjoy your meal. Let\'s Knosh Together !';
						// echo view('mail.notifyme.notifyme_mail',$data);exit;
						\Mail::send('mail.notifyme.notifyme_mail',$data,function($message) use($from,$mailid){
							$message->from($from,CNF_APPNAME)->subject('Chef is ready to serve you food now')->to($mailid);
						});
						$notified = true;
					}
					if($value['user']['mobile_token'] != '' && $value['user']['mobile_token'] != null){
						FCM($value['user']['mobile_token'],'NotifyMe Alert',$value->chef->name." is currently online");
						$notified = true;
					}
					if($notified == true) {	
						$value->delete($value->id);
					}
				}
			}
			$status = 200; $response['message'] = "Notification sended successfully.";
		}
		return \Response::json($response,$status);
	}

	public function blastNotification(Request $request){
		$guard = ($request->from == 'mobile') ? 'api' : 'web';
		$auth_id	= auth($guard)->user()->id;
		$auth_role	= auth($guard)->user()->role;
		$status = 200;
		if($auth_role == 3) {
			$response['notifications'] = BlastnotificationLogs::where('chefs',$auth_id)->orWhere('chefs','all_chefs')->get();
		} else if($auth_role == 2) {
			$response['notifications'] = BlastnotificationLogs::where('users',$auth_id)->orWhere('chefs','all_users')->get();
		}
		$response['message']  	   = "Blast Notifications are listed.";
		return \Response::json($response,$status); 
	}
}