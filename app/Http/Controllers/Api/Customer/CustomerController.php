<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Partner\PartnerController as PartnerControl;
use App\Http\Controllers\Front\FrontendController as FrontControl;
use Illuminate\Http\Request;
use App\Models\Chefs;
use App\Models\Bookmarks;
use App\Models\Favourites;
use App\Models\Wishlist;
use App\Models\Menuitems;
use App\Models\User;
use App\Models\Banner;
use App\Models\PopularRecipe;
use App\Models\Blogs;
use App\Models\WhatsTrending;
use App\Models\Comment;
use App\Models\Commentlike;
use App\Models\Cuisines;
use App\Models\Category;
use App\Models\Restaurants;
use App\Models\Explore;
use App\Models\Address;
use App\Models\Orderdetail;
use App\Models\Referralsettings;
use App\Models\Homecontent;
use App\Models\Commondatas;
use App\Models\WalletHistory;
use App\Models\Notifyme;
use App\Models\Reels;
use App\Models\Homeeventsection;
use Illuminate\Support\Facades\Hash;
use Auth, Mail, DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Traits\LikeScopeTrait;
use Illuminate\Database\Eloquent\Builder;


/**
 * @author : Suganya
 * @return \Illuminate\Http\JsonResponse
 */
class CustomerController extends Controller
{

	/*public function __construct( Request $request )
	{
		$this->perPageCount = 5;
		if (!is_null(\Request::segment('1')) && \Request::segment('1') == 'api') {
			$request->request->add(['from'=>'mobile']);
		}else{
			$request->request->add(['from'=>'web']);
		}
		$this->method	= \Request::method();
		$this->segment	= \Request::segment(1);
	}*/

	public function webview( Request $request, $page)
	{
		$front = new FrontControl;
		return $front->showpage($request, $page,"api");
	}

	public function homepage( Request $request)
	{
		$pageNumber = isset($request->pageNumber) ? $request->pageNumber: 1;
		$perPage	= 10;
		if (isset($request->pageNumber) && $request->pageNumber != '' && is_numeric($request->pageNumber)) {
			$pageNumber = $request->pageNumber;
		}
		$rules = array();
			// $rules['latitude']	= ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'];
			// $rules['longitude']	= ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'];
		if (isset($request->seemore) && $request->seemore != '') {
			$rules['seemore']	= ['required','in:nearByChefs,celebrityChefs,popularChefs,popularNearYou,topRatedChefs,foodblock,chefevent,homeevent,reels'];
		}
		$this->validateDatas($request->all(),$rules,[],[]);
		if (isset($request->explore) && $request->explore != '') {
			$response['exploreseemore'] = $this->exploreseemore();
			return \Response::json($response,200);
		}

		$select	= ['id','name','cuisine_type','avatar','budget'];
		$seemore = ['tChefs'=>'topRatedChefs'];
		if(isset($request->device) && $request->device == 'android') {
			$seemore = ['tChefs'=>'topRatedChefs','cChefs'=>'celebrityChefs'];
		}

		if($request->from != 'mobile'  || ($request->from == 'mobile' && isset($request->seemore))){
			$seemore= ['nChefs'=>'nearByChefs','cChefs'=>'celebrityChefs','tChefs'=>'topRatedChefs','popular'=>'popularNearYou','pChefs'=>'popularChefs'];
		}

		$chefs	= Chefs::commonselect()->with('active_offers')->approved()->haveinfo()->havemenus()->where('type','!=','event')->where('type','!=','home_event');

		if($request->seemore == 'chefevent' || $request->seemore == 'homeevent') {	
			$currentDate = date('Y-m-d H:i:s');
			$type = ($request->seemore == 'chefevent') ? 'event' : 'home_event';
			$chefs = Chefs::commonselect()->addSelect('type')->approved()->haveinfo()->where('type',$type)->whereHas('event_fooditems')->whereHas('singlerestaurant',function($query) use($currentDate,$type){
				if($type == "event") {
					$query/*->where('mode','open')*/->where('status','approved')->whereRaw('? not between off_from and off_to', [$currentDate]);
				} elseif($type == "home_event") {
					$query->where('mode','open')->where('status','approved');
				}
			});
		}

		if (isset($request->cuisine) && $request->cuisine != '') {
			$cuisine	= explode(',',$request->cuisine);
			$chefs		= $chefs->findinset('cuisine_type',$cuisine);
		}

		if (isset($request->tag) && $request->tag != '') {
			$chefs = $chefs->filter('restaurants','tags',$request->tag);
		}

		if (isset($request->offer) && $request->offer != '') {
			$chefs = $chefs->filter('offers','offers',$request->offer)->with('offers');
		}

		$cChefs = clone $chefs; $nChefs = clone $chefs; $tChefs = clone $chefs; $pChefs = clone $chefs; $popular = clone $chefs; $promoted = clone $chefs;
		$promoted	= $promoted->promoted()->get();
		if($request->seemore != 'foodblock' && $request->seemore != 'chefevent' && $request->seemore != 'homeevent' && $request->seemore != 'reels') {
			foreach ($seemore as $key => $value) {
				$continue	= true;
				if (isset($request->seemore) && $request->seemore != '') {
					$ret	= array_search($request->seemore,$seemore);
					$aArray	= $request->seemore;
					$aKey	= $$ret;
					$continue = false;
				} else {
					$aArray	= $value;
					$aKey	= $$key;
				}
				$intruders = array();
				$intruders['chefType']	= $value;
				$intruders['needFrom']	= isset($request->from) ? $request->from : '';
				$intruders['seemore']	= (isset($request->seemore) && ( in_array($request->seemore,array('celebrityChefs')) || in_array($request->seemore,array('popularChefs'))))  ? true : false;
				$aKey->with(['restaurants' =>function($query) use($intruders) {
				$query->commonselect()->when((($intruders['chefType'] == 'nearByChefs' || $intruders['seemore']) /*&& $intruders['needFrom'] == 'web'*/),function($mQuery) {
					$mQuery->with('getRandomFoodItems');
				});
			}]);
				$Chefs	= $aKey/*->nearby($request->latitude,$request->longitude)*/->get()->pluck('id');
				if ($aArray == 'nearByChefs') {
					$record_count = (isset($request->call) && $request->call == 'onscroll') ? 5 : 20;
					if (!isset($request->cuisine_id) && $request->cuisine_id == '') {
						$aKey = $aKey->popular();
					}
					$aKey	= self::Homepagemap($aKey->orderBy('ordering')->paginate($record_count, ['*'], 'page', $pageNumber),$promoted);
				} elseif ($aArray == 'topRatedChefs') {
					$from = isset($request->seemore) && $request->seemore == 'topRatedChefs' ? '' : 'toprated';
					$aKey	= self::Homepagemap($aKey/*->notpromoted()->inRandomOrder()*/->toprated()->orderByRaw("FIELD(celebrity , 'yes', 'no') ASC")->paginate($perPage, ['*'], 'page', $pageNumber),$promoted,$from);
				} elseif ($aArray == 'celebrityChefs') {
					$record_count = (isset($request->call) && $request->call == 'onscroll') ? 5 : 20;
					$aKey	= self::Homepagemap($aKey/*->inRandomOrder()*/->celebrity()->orderBy('ordering')->paginate($record_count, ['*'], 'page', $pageNumber),$promoted);
				} elseif ($aArray == 'popularChefs') {
					$aKey	= self::Homepagemap($aKey/*->inRandomOrder()*/->popular()->orderBy('ordering')->paginate(20, ['*'], 'page', $pageNumber),$promoted);
				} elseif ($aArray == 'popularNearYou') {
					$arrFood	= $popular = [];
					$aKey	= $popular;
				}
				$response[$aArray]	= (count($aKey) > 0) ? $aKey : (object) [];
				if (!$continue) break;
			}
		}
		if (!isset($request->seemore) && $request->seemore == '' || ($request->from == 'mobile' && $request->seemore == 'foodblock')) {
			if($request->from != 'mobile' || ($request->from == 'mobile' && $request->seemore == 'foodblock')) {
				$response['explore']		= self::ExploreData();
				$response['popularRecipe']	= self::PopularRecipe();
				$response['blogs']			= self::Blogs();
				$response['whatsTrending']	= self::whatsTrending();
				$response['sectionTitles']	= self::HomepageContent();
			}
		}

		if($request->from == 'web' && $request->seemore == "chefevent" || $request->from == 'mobile' && $request->seemore == "chefevent") {
			$record_count = (isset($request->call) && $request->call == 'onscroll') ? 5 : $perPage;
			$response['chefevent']  =  self::Homepagemap($chefs->orderBy('ordering')->paginate($record_count, ['*'], 'page', $pageNumber),'','chefevent');
		}
		if($request->from == 'web' && $request->seemore == "reels" || $request->from == 'mobile' && $request->seemore == "reels") {
			$response['reels']  =  self::Reels($perPage,$pageNumber);	
			return \Response::json($response,200);
		}

		if($request->from == 'web' && $request->seemore == "homeevent" || $request->from == 'mobile' && $request->seemore == "homeevent") {
			$record_count = (isset($request->call) && $request->call == 'onscroll') ? 5 : $perPage;
			$response['homeevent']  =  self::Homepagemap($chefs->orderBy('ordering')->paginate($record_count, ['*'], 'page', $pageNumber),'','homeevent');
		}
		if($request->ajax() && $request->seemore == '') {
			$response['popularRecipe']	= self::PopularRecipe();
			$response['blogs']			= self::Blogs();
			$response['whatsTrending']	= self::whatsTrending();
			$response['sectionTitles']	= self::HomepageContent();
		}
		$response['exploreseemore'] = $this->exploreseemore();
		$response['explorecuisines'] = self::ExploreCuisinesData();
		$response['banner']			= self::Banner();
		$response['reels']          = self::Reels($perPage,$pageNumber);
		return \Response::json($response,200);
	}

	public function getPopularRecipe(Request $request)
	{
		$status		= 200;			
		$message	= "popular recipe get successfully.";
		//Tam this is for single wishlist to edit in web.
		if (isset($request->id) && $request->id != 0  && $request->id != '')
			$popular	= PopularRecipe::active()->where('id',$request->id)->first();
		$response['message']	= $message;
		$response['popularRecipe']	= $popular;
		return \Response::json($response,$status);
	}

	public function getBlog(Request $request)
	{
		$status		= 200;			
		$message	= "blog get successfully.";
		//Tam this is for single wishlist to edit in web.
		if (isset($request->id) && $request->id != 0  && $request->id != '')
			$blog	= Blogs::active()->where('id',$request->id)->first();
		$response['message']	= $message;
		$response['blog']	    = $blog;
		return \Response::json($response,$status);
	}

	public function Homepagemap($query,$promoted,$from = '')
	{
		$query/*->where('avalability','avail')*/->transform(function ($result, $key) use ($promoted,$from) {
			if (self::IsPrime($key+1) == 0) {
				// echo "<pre>";print_r($promoted[0]->id);echo "<br>";
				// exit();
				/*$result		= (count($promoted) > 0) ? $promoted[0] : $result;
				unset($promoted->{0});
				$promoted	= $promoted->values(); // Array values of Object*/
			}
			$result->description	= (count($result->restaurants) > 0) ? $result->restaurants[0]->description : '';
			$result->cuisines		= $result->makeHidden('cuisine_type');
			$result->budgetName		= $result->getChefBudget($result->restaurants[0]->id);
			if($from == '') {
				$result->get_vendor_food_details = $result->getVendorFoodDetails;
			} elseif($from == 'chefevent' || $from == 'homeevent') {
				$result->get_vendor_food_details = $result->event_fooditems;
				$result->get_vendor_food_details->makeHidden(['theme_detail','preference_detail','meal_detail']);
				$result->makeHidden('event_fooditems'); 
			} 
			$result->makeHidden('restaurants');
			return $result;
		})/*->all()*/;
		return $query;
	}

	function IsPrime($n)
	{
		for ($x=2; $x <= $n; $x++) {
			$var = $n % $x;
			if ($n % $x == 0) {
				return 0;
			}
		}
		return 1;
	}

	public function ExploreData()
	{
		return Explore::select('id','name','slug','image')->where('id',1)->get();
	}

	public function ExploreCuisinesData()
	{
		return Cuisines::select('id','name','image')->where('explore','yes')->get();
	}

	public function HomepageContent()
	{
		return Homecontent::select('section1','section2','section3')->first();
	}

	public function Banner()
	{
		return Banner::active()->get();
	}

	public function Reels($perpage=0, $page_no=1)
	{
		$from_date = date('Y-m-d H:i:s');
		$reel	= Reels::with('chef')->whereDate('validity_date_time', '>=', $from_date)->orderByDesc('id')->paginate($perpage, ['*'], 'page', $page_no);
		$reel->transform(function ($result) {
			$result->time	= $result->created_at->diffForHumans();
			if ($result->chef != '' && $result->chef !== null) {
				$result->chef->append(['ratings','avatar']);
				$result->makeHidden(['validity_date_time','status','selected_chef_id','is_chef_selected']);
				$result->chef->makeHidden(['reviewscount','cuisines','avalability','is_bookmarked','documentverify','chef_description','socket_subscribe_name','socket_room_name','room','is_notify','event_location','event_time']);
			}
			return $result;
		});
		return $reel;
	}

	public function popularRecipe()
	{
		return PopularRecipe::active()->paginate(6);
	}

	public function Blogs()
	{
		return Blogs::active()->paginate(6);
	}

	public function whatsTrending()
	{
		return WhatsTrending::active()->paginate(6);
	}

	public function Explore( Request $request)
	{
		$rules['keyword']	= 'required';
		// $rules['latitude']	= 'required';
		// $rules['longitude']	= 'required';
		$this->validateDatas($request->all(),$rules);
		$keyword	= strtolower($request->keyword);
		$aData		= [];
		$returnname = 'aData';
		switch ($keyword) {
			case 'explorecuisines':
			$aData = self::ExploreCuisinesData();
			break;
			case 'cuisines':
			if (isset($request->cuisine_id) && $request->cuisine_id != '') {
				$request['seemore'] = 'nearByChefs';
				$request['cuisine'] = $request->cuisine_id;
				$aData				= self::homepage($request)->original['nearByChefs'] ?? [];
			} else {
				$aData = Cuisines::get();
			}
			break;
			case 'nearbychefs':
			$request['seemore'] = 'nearByChefs';
			$aData				= self::homepage($request)->original['nearByChefs'] ?? [];
			break;
			case 'popular':
			$request['seemore']	= 'popularNearYou';
			$aData				= self::homepage($request)->original['popularNearYou'] ?? [];
			if($request->from == 'mobile') { $returnname  = 'aDishes'; }
			break;
			default:
			$request['seemore']	= 'nearByChefs';
			$request['tag']		= $keyword;
			$aData				= self::homepage($request)->original['nearByChefs'] ?? [];
		}
		return response()->json(['status' => true,$returnname => $aData], 200);
	}

	public function chefinfo(Request $request)
	{
		$rules	= $nicenames	= $data	= array();
		$rules['id']	= 'required|numeric';
		$nicenames['id']= "Chef Identification";
		$this->validateDatas($request->all(),$rules,[],$nicenames);

		$pageNumber	= 1;
		$perPage	= $this->paginate;
		if (isset($request->pageNumber) && $request->pageNumber != '' && is_numeric($request->pageNumber)) {
			$pageNumber = $request->pageNumber;
		}

		$status = 503;
		$data   = array();
		$message= "Chef is unavailable in our service";
		// \DB::enableQueryLog();
		$chef	= Chefs::commonselect()->approved()->with(['getFirstRestaurant'])->haveinfo()->havemenus()->where('id',$request->id)->first();
		// dd(\DB::getQueryLog());
		if (!empty($chef)) {
			$restaurant	= $chef->getFirstRestaurant;
			$resId		= '';
			if (!is_null($restaurant) && !empty($restaurant)) {
				$chef->append(['reviewscount','ratings','avatar']);
				$chef->description	= $chef->getFirstRestaurant->description;
				$chef->address		= $chef->getFirstRestaurant->adrs_line_1;
				$chef->budgetName	= $chef->getFirstRestaurant->budget_name;
				$chef->restaurantDetails = $chef->getApprovedRestaurant()
				->with(['getApprovedFoodItems' => function($query) use($perPage,$pageNumber){
					$query->commonselect()
					->paginate($perPage, ['*'], 'page', $pageNumber);
				}])
				->addSelect('id','budget')
				->paginate($perPage, ['*'], 'page', $pageNumber);
				$chef->makeHidden('getFirstRestaurant');
				$data['chefinfo']	= $chef;
				$status		= 200;
				$message	= "Chef is available in our service";
			}
		}
		$data['status']		= $status;
		$data['message']	= $message;
		return \Response::json($data,$status);
	}

	public function cartData($userCart,$restaurant_id,$guard)
	{
		if ($guard == 'api') {
			return app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@cartData',[
				'userCart' => $userCart,'from' => 'chefinfo','restaurant_id' => $restaurant_id]);
		} else {
			return app()->call('App\Http\Controllers\Api\V1\Emperica\CartController@cartData',[
				'userCart' => $userCart]);
		}
	}

	public function chefCategories(Request $request)
	{
		$status	= 422;
		$chef	= Chefs::with(['food_items.menuscatogory','singlerestaurant'])->approved()->find($request->id);
		$category	= chefApprovedMenuCatgories($chef);
		if (empty($chef)) {
			$response['message']	= 'error';
			return \Response::json($response,$status);
		}
		if (!empty($chef) && !empty($category)) {
			$categories = Category::whereIn('id',$category);
			if ($chef->singlerestaurant->category_order == '') {
				// $categories = $categories->orderBy('name','ASC');
			} else {
				$catrder	= $chef->singlerestaurant->category_order;
				$categories = $categories->orderByRaw("FIELD(id,$catrder)");
			}
			$categories	= $categories->get();
			$status		= 200;
			$response['categories']	= $categories;
			$response['message']	= 'success';
		}
		return \Response::json($response,$status);
	}

	public function chefinfonew(Request $request)
	{
		$guard	= ($request->from == 'mobile') ? 'api' : 'web';
		$user	= auth($guard)->user();
		$userId	= (!empty($user)) ? $user->id : 0 ;

		$rules	=	$nicenames	= $data	= array();
		$rules['id']	= 'required|numeric';
		$nicenames['id']= "Chef Identification";
		$this->validateDatas($request->all(),$rules,[],$nicenames);

		$menupage	= $mpage = 1;
		$perPage	= 30;
		if (isset($request->menupage) && $request->menupage != '' && is_numeric($request->menupage)) {
			$menupage = $request->menupage;
		}
		if (isset($request->menuPage) && $request->menuPage != '' && is_numeric($request->menuPage)) {
			$mpage = $request->menuPage;
		}

		$status	= 503;
		$data	= array();
		$message= "Chef is unavailable in our service";
		$chef_type = cheftype($request->id);
		$chef	= Chefs::commonselect()->addselect('type')->approved();
		if($chef_type == "event") {
			$chef = $chef->whereHas('ChefRestaurant',function($requery){
				$requery->where('event_datetime','!=','');
			});
		}
		$chef   = $chef->with(['publishedreviews' => function($qry){
			$qry->user();
		},'ChefRestaurant' => function($query) use($perPage,$menupage,$chef_type) {
			$query->commonselect()->addSelect('fssai','off_from','off_to','working_days','type','event_datetime','sector','location');
			if($chef_type == "event" || $chef_type == "home_event") {
				$query->with('approvedTickets', function($squery) use($perPage, $menupage) {
					$squery->paginate($perPage, ['*'], 'menupage', $menupage);
				});
			} else {
				$query->with('approvedDishes', function($squery) use($perPage, $menupage) {
					$squery->paginate($perPage, ['*'], 'menupage', $menupage);
				});
			}
		}
	])->haveinfo();
		if(cheftype($request->id) != 'event' && cheftype($request->id) != 'home_event') {
			$chef = $chef->havemenus();
		} else {
			$chef = $chef->havetickets();
		} 
		$chef = $chef->where('id',$request->id)->first();
		if(cheftype($request->id) == 'event' || cheftype($request->id) == 'home_event') {
			if(!empty($chef)) {
				$chef->ChefRestaurant->approved_dishes = $chef->ChefRestaurant->approvedTickets;	
				unset($chef->ChefRestaurant->approvedTickets);
			}
		}
		if (!empty($chef)) {
			$categoryId			= (isset($request->category_id)) ? $request->category_id : '';
			$menuId				= (isset($request->menu_id)) ? $request->menu_id : '';
			$catId			    = (isset($request->catid)) ? $request->catid : '';
			$PartnerControl		= new PartnerControl;
			$categoryData		= $PartnerControl->fetchChefMenu($chef->ChefRestaurant->vendor_id,$chef->ChefRestaurant->id,$perPage,'approved','1',$mpage,'',$categoryId,$menuId,$catId)->getFirstRestaurant->categories;
			$categoryCount 		= $categoryData->count();
			$categoryData 		= $categoryData
			/*->reject(function ($value, $key) {
				return $value->menuitems->isEmpty();
			})*/->toArray();
			$chef->ChefRestaurant['categories']			= array_values($categoryData);
			$chef->ChefRestaurant['category_perPage']	= ($categoryCount);
			$chef->ChefRestaurant['categories_count']	= ($perPage);
			$uCartQuery			= uCartQuery($userId, request('cookie'));
			$ucartData			= self::cartData($uCartQuery,$chef->ChefRestaurant->id,$guard)['cartData'];
			$ucart				= clone ($uCartQuery);
			$ucartData['price']	= (float) $ucart->sum('price');
			$ucartData['count']	= $uCartQuery->count();
			$chef->cart_detail	= (!empty($ucartData)) ? $ucartData : (object) [];
			$status				= 200;
			$message			= "Chef is available in our service";
			$data['chefinfo']	= $chef;
			$data['offtime_dates'] = [];
			if($chef->ChefRestaurant->off_from && $chef->ChefRestaurant->off_to != null){
				$off_from = strtotime($chef->ChefRestaurant->off_from);
				$off_to   = strtotime($chef->ChefRestaurant->off_to); 
				for($start = $off_from;$start <= $off_to;$start += (86400)){
					$dates[] = date('Y-m-d',$start);  
				}
				$data['offtime_dates'] = $dates;
			}
			$workdays = json_decode($chef->ChefRestaurant->working_days, true);
			$dds =[];
			$data['offtime_days'] = [];
			if($workdays){
				foreach($workdays as $kk=>$val){
					if($val==1){
						$dds[]= $kk;
					} 
				}
			}
			$data['offtime_days'] = $dds;
		}
		// echo "<pre>";print_r($data);exit;
		$data['status']		= $status;
		$data['message']	= $message;
		return \Response::json($data,$status);
	}

	/* Book mark API Begins */
	public function userbookmarks(Request $request)
	{
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$user		= auth($guard)->user();
		$userId		= (!empty($user)) ? $user->id : 0 ;
		$bPage		= $menuPage = 1;
		$perPage	= 30;
		$vendorId	= 0;

		$rules = array();
		if (isset($request->bPage) && $request->bPage != '' && is_numeric($request->bPage)) {
			$bPage = $request->bPage;
		}
		if (isset($request->menuPage) && $request->menuPage != '' && is_numeric($request->menuPage)) {
			$menuPage = $request->menuPage;
			$rules['vendor_id'] = 'required';
			if (isset($request->vendor_id) && $request->vendor_id != '' && is_numeric($request->vendor_id)) {
				$vendorId = $request->vendor_id;
			}
		}

		$this->validateDatas($request->all(),$rules);

		$bookmarkLists = User::select(['id'])->where('id',$userId)
		->with(['getBookmarks'=>function($query) use($perPage,$bPage,$menuPage, $vendorId){
			$query->commonselect();
			if ($vendorId != 0) {
				$query->where('vendor_id',$vendorId);
			}
			$query->orderBy('id','DESC')
			->with(['getVendorDetails' => function($vendorQuery){
				$vendorQuery->commonselect();
				$vendorQuery->with(['getFirstRestaurant' => function($address) {$address->addSelect('id','vendor_id','budget');}]);
			},
			'getVendorFoodDetails' => function($vendorFoodQuery) use($perPage,$menuPage){
				$vendorFoodQuery->commonselect()->approved()->paginate($perPage, ['*'], 'menuPage', $menuPage);
			}
		])->paginate($perPage, ['*'], 'bookmarkpage', $bPage);
		}])->first();
		$bookmarkLists->makeHidden('role_id');
		// echo "<pre>";print_r($bookmarkLists);exit();

		$response = ['bookmarks' => $bookmarkLists];
		return \Response::json($response,200);
	}

	public function updatebookmark(Request $request)
	{
		$rules	= $nicenames	= array();
		$rules['vendor_id']		= 'required|numeric';
		$nicenames['vendor_id']	= "Vendor Identification";
		$this->validateDatas($request->all(),$rules,[],$nicenames);

		$userId		= \Auth::user()->id;
		$vendorId	= $request->vendor_id;
		$status		= 503;
		$action		= false;
		$message	= "Bookmarking is failure try again.";
		$exists		= Bookmarks::where('user_id',$userId)->where('vendor_id',$vendorId)->exists();
		if ($exists) {
			Bookmarks::where('user_id',$userId)->where('vendor_id',$vendorId)->delete();
			$message = "Bookmarking is removed successfully.";
			$status = 200;
		} else {
			$vendorExists = Chefs::where('id',$vendorId)->exists();
			if ($vendorExists) {
				$creation				= new Bookmarks();
				$creation->user_id		= $userId;
				$creation->vendor_id	= $vendorId;
				$creation->created_at	= date('Y-m-m h:i:s');
				$creation->updated_at	= date('Y-m-m h:i:s');
				$creation->save();
				if ($creation) {
					$action	= true;
					$message= "Bookmarking is Added successfully.";
					$status	= 200;
				}
			} else {
				$message = "Vendor is not available at the moment.";
			}
		}
		$response['success']	= $action;
		$response['message']	= $message;
		return \Response::json($response,$status);
	}
	/* Book mark API End */

	public function userfavourites(Request $request)
	{
		$guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$user		= auth($guard)->user();
		$userId		= (!empty($user)) ? $user->id : 0 ;
		$fPage		= $menuPage = 1;
		$perPage	= $this->paginate;
		$vendorId	= 0;

		$rules = $intruders = array();
		if (isset($request->fPage) && $request->fPage != '' && is_numeric($request->fPage)) {
			$fPage = $request->fPage;
		}
		if (isset($request->menuPage) && $request->menuPage != '' && is_numeric($request->menuPage)) {
			$menuPage = $request->menuPage;
			$rules['vendor_id'] = 'required';
			if (isset($request->vendor_id) && $request->vendor_id != '' && is_numeric($request->vendor_id)) {
				$vendorId = $request->vendor_id;
			}
		}
		$intruders['fPage']		= $fPage;
		$intruders['fPerPage']	= $perPage;
		$intruders['mPage']		= $menuPage;
		$intruders['mPerPage']	= $perPage;
		$intruders['vendorId']	= $vendorId;
		$intruders['userId']	= $userId;
		$favouriteLists = User::where('id',$userId)
		->with(['getFavourites'=>function($query) use($intruders){
			$query->commonselect();
			if ($intruders['vendorId'] != 0) {
				$query->where('vendor_id',$intruders['vendorId']);
			}
			$query->orderBy('id','DESC')
			->with(
				['getVendorDetails' => function($vendorQuery) use($intruders) {
					$vendorQuery->commonselect();	
					$vendorQuery->with(['getFirstRestaurant' => function($address) {$address->addSelect('id','vendor_id','adrs_line_1','budget');}]);
				},
				'getAllFavoriteAlter' => function($subQuery) use($intruders) {
					$subQuery->addSelect('menu_id','menu_items.*')->where('user_id',$intruders['userId'])->paginate($intruders['mPerPage'], ['*'], 'page', $intruders['mPage']);
				}
			]
		)
			->groupBy('vendor_id')
			->paginate($intruders['fPerPage'], ['*'], 'page', $intruders['fPage']);
		}])->addSelect('id')->first()->makeHidden([ 'role_id']);

		$response['favourites']	= $favouriteLists;
		return \Response::json($response,200);
	}

	public function updatefavourite(Request $request)
	{
		$rules				= array();
		$rules['menu_id']	= 'required|numeric';
		$nicenames				= array();
		$nicenames['menu_id']	= "Menu Identification";
		$this->validateDatas($request->all(),$rules,[],$nicenames);
		$userId	= \Auth::user()->id;
		$menuId	= $request->menu_id;

		$status	= 503;
		$action	= false;
		$message	= "Favorite Adding is failure try again.";
		$exists		= Favourites::where('user_id',$userId)->where('menu_id',$menuId)->exists();
		if ($exists) {
			Favourites::where('user_id',$userId)->where('menu_id',$menuId)->delete();
			$message = "Favourites is removed successfully.";
			$status = 200;
		} else {
			$menuExists = Menuitems::where('id',$menuId)->first();
			if (!empty($menuExists)) {
				$creation				= new Favourites();
				$creation->user_id		= $userId;
				$creation->vendor_id	= $menuExists->vendor_id;
				$creation->restaurant_id= $menuExists->restaurant_id;
				$creation->menu_id		= $menuExists->id;
				$creation->created_at	= date('Y-m-m h:i:s');
				$creation->updated_at	= date('Y-m-m h:i:s');
				$creation->save();

				if ($creation) {
					$action		= true;
					$message	= "Favourites is Added successfully.";
					$status		= 200;
				}
			} else {
				$message = "Refersh your page then do again.";
			}
		}
		$response['success']	= $action;
		$response['message']	= $message;

		return \Response::json($response,$status);
	}

	public function userwishlists(Request $request)
	{
		$userId		= \Auth::user()->id;
		$status		= 200;
		$pageNumber = isset($request->pageNumber) ? $request->pageNumber: 1;
		$perPage	= 10;

		if (isset($request->pageNumber) && $request->pageNumber != '' && is_numeric($request->pageNumber)) {
			$pageNumber = $request->pageNumber;
		}
		$message	= "User wishlists are listed successfully.";
		//Tam this is for single wishlist to edit in web.
		if (isset($request->id) && $request->id != 0  && $request->id != '')
			$wishlists	= Wishlist::commonselect()->where('user_id',$userId)->where('id',$request->id)->paginate($perPage, ['*'], 'page', $pageNumber);
		else
			$wishlists	= Wishlist::commonselect()->where('user_id',$userId)->paginate($perPage, ['*'], 'page', $pageNumber);
		$response['message']	= $message;
		$response['wishlists']	= $wishlists;
		return \Response::json($response,$status);
	}

	public function updatewishlist(Request $request)
	{
		$rules		= array();
		$nicenames	= array();
		$update		= false;
		$message	= "Please Try Again...";
		$status		= 500;

		if (isset($request->id) && $request->id !='') {
			$rules['id']		= 'required|numeric';
			$nicenames['id']	= "Wishlist Identification";
			$update				= true;
		} else {
			$rules['title']				= 'required';
			$rules['description']		= 'required';
			$nicenames['title']			= "Food Title";
			$nicenames['description']	= "Food Description";
		}
		$this->validateDatas($request->all(),$rules,[],$nicenames);

		$userId = \Auth::user()->id;

		if ($update) {
			$updateWish	= Wishlist::find($request->id);
			if (!empty($updateWish) && $updateWish->user_id == $userId) {
				$update	= false;
				if (isset($request->title) && $request->title != "") {
					$updateWish->title = $request->title;
					$update	= true;
				}
				if (isset($request->description) && $request->description != "") {
					$updateWish->description = $request->description;
					$update	= true;
				}
				if ($update) {
					$updateWish->save();
					$message	= "Your wishlist is updated successfully.";
					$status		= 200;
				} else {
					$status		= 200;
					$message	= "Nothing to update your wishlist.";					
				}
			} else {
				$message		= "You does not have a permision to update this.";
			}
		} else {
			$createWish			= new Wishlist();
			$createWish->user_id	= $userId;
			$createWish->title		= $request->title;
			$createWish->description= $request->description;
			$createWish->save();
			if ($createWish) {
				$message	= "Your wishlist is added successfully.";
				$status		= 200;
			}
		}
		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function deletewishlist(Request $request)
	{
		$rules		= array();
		$rules['id']= 'required|numeric';
		$nicenames		= array();
		$nicenames['id']= "Wishlist Identification";
		$message	= "Please Try Again...";
		$status		= 500;

		$this->validateDatas($request->all(),$rules,[],$nicenames);
		$userId		= \Auth::user()->id;
		$deleteWish	= Wishlist::find($request->id);

		if (!empty($deleteWish) && $deleteWish->user_id == $userId) {
			$deleteWish->delete();
			$message = "The wishlist is removed successfully.";
			$status = 200;
		} else {
			$message = "You Does not have a permision to update this.";
		}

		$response['message']	= $message;
		return \Response::json($response,$status);
	}

	public function menuinfo(Request $request)
	{
		$rules['menu_id']		= 'required|numeric|exists:menu_items,id';
		$nicenames['menu_id']	= "Menu Identification";
		$from	= (\Request::get('from') == 'mobile') ? 'api' : 'web';
		$response= $this->validateDatas($request->all(),$rules,[],$nicenames,$from);
		
		if ($from == 'web' && !empty($response)) {
			return \Response::json($response, 422);
		}
		$user		= $this->authCheck();
		$userId		= $user['userId'];
		$userData	= $user['user'];
		$status		= 503;
		$message	= "Menu is not available in our service";
		$response	= [];
		$menuItems	= Menuitems::approved()->with('comments',function ($query)
		{
			$query->published()->comment();
		})->find($request->menu_id);
		if (!empty($menuItems)) {
			$status		= 200;
			$message	= "Menu is available in our service";
			$response['menu_items']  = $menuItems;
		}
		if ($request->from == 'mobile') {
			$userCart	= uCartQuery($userId, request('cookie'));
			$returncart	= clone ($userCart);
			$response['price']	= $returncart->sum('price');
			$response['count']	= $userCart->count();
		}

		$response['message'] = $message;
		return \Response::json($response,$status);
	}

	public function me( Request $request)
	{
		$guard = ($request->from == 'mobile') ? 'api' : 'web';
		return auth($guard)->user();
	}

	public function search(Request $request)
	{
		$rules				= array();
		$rules['keyword']	= 'required';
		// $rules['latitude']	= ['required'];
		// $rules['longitude']	= ['required'];
		$this->validateDatas($request->all(),$rules);
		$keyword	= addslashes(strtolower($request->keyword));
		$Cuisine	= Cuisines::Where('name','like', '%'.$keyword.'%')->pluck('id')->toArray();
		$category   = Category::where('name','like', '%'.$keyword.'%')->where('type','food_category')->pluck('id')->toArray();
		$tags 		= Commondatas::where('name','like', '%'.$keyword.'%')->where('type','tag')->first(['id']);
		$c			= 0;
		if(count($Cuisine) > 0)
			$c	= implode('|',$Cuisine);

				// $Result	= Chefs::has('food_items', '>', 0)
				// ->with('singlerestaurant', function ($squery) {
				// $squery->addSelect('id','vendor_id','budget')
				// ->available();
				// })->commonselect()
		// ->nearby($request->latitude,$request->longitude)
		// ->avail()
				// ->approved()
				// ->haveinfo()
				// ->havemenus()
				// ->where(function($subquery) use($keyword,$Cuisine,$c){
				// $subquery->OrWhere('name','like', '%'.$keyword.'%')
				// ->orWhereRaw('cuisine_type REGEXP("('.$c.')")');
				// })
		/*->Orwhere(function ($subquery) use($keyword,$request){
			$subquery->nearby($request->latitude,$request->longitude);
		})*//*->OrwhereHas('food_items', function (Builder $query) use($keyword) {
			$query->where('name', 'like', '%'.$keyword.'%');
		})*/
				// ->with(['food_items' =>function($query) use($keyword){
				// $query->where('name', 'like', '%'.$keyword.'%')
				// ->orderBy(DB::raw('case when name LIKE "'.$keyword.'%" then 1 when name LIKE "%'.$keyword.'%" then 2 else 2 end'))
				// ->orderBy('name','ASC')
				// ->limit(4);
				// }])
				// ->orderBy(DB::raw('case when name LIKE "%'.$keyword.'%" then 1 when cuisine_type REGEXP("('.$c.')") then 2 else 3 end'))
		/*->orderBy('name')*/
				// ->paginate(10);


		$Result	= Chefs::commonselect()
		->approved()
		->haveinfo()
		->havemenus()
		->where(function($orWhereHasMain) use($keyword,$Cuisine,$c,$category,$tags){
			$orWhereHasMain->where(function($orWhereHasSub) use($keyword,$Cuisine,$c){
				$orWhereHasSub->where('profile_name','like', '%'.$keyword.'%');
				if($c != 0)
					$orWhereHasSub->orWhereRaw('cuisine_type REGEXP("('.$c.')")');
			})
			->orWhereHas('food_items' , function($queryHas) use($keyword,$category){
				$queryHas->where('name', 'like', '%'.$keyword.'%')->orWhereIn('main_category',isset($category) ? $category : '');
			});
			if($tags){
				$orWhereHasMain->orWhereHas('singlerestaurant',function($singleHas) use($tags){
					$singleHas->whereIn('tags',isset($tags) ? $tags : '');
				});
			}

		})->with([
			'food_items' =>function($fquery) use($keyword,$category){
				$fquery->whereIn('main_category',isset($category) ? $category : '')->orWhere('name', 'like', '%'.$keyword.'%')
				->orderBy(DB::raw('case when name LIKE "'.$keyword.'%" then 1 when name LIKE "%'.$keyword.'%" then 2 else 2 end'))
				->orderBy('name','ASC')
				->limit(5);
				return $fquery;
			},
			'singlerestaurant'=> function ($squery) {
				$squery->addSelect('id','vendor_id','budget')->available();
			}
		])->orderBy(DB::raw('case when name LIKE "%'.$keyword.'%" then 1 when cuisine_type REGEXP("('.$c.')") then 2 else 3 end'))
		->paginate(10);
		$response['result'] = $Result;
		return \Response::json($response,200);
	}

	public function menuComment(Request $request)
	{
		$guard = ($request->from == 'mobile') ? 'api' : 'web';
		$status		= 200;
		$auth_id	= auth($guard)->user()->id;
		$auth_role	= auth($guard)->user()->role;
		$cmessage	= $rules = $nicenames	= [];

		if ($this->method == 'POST') {
			$rules['food_id']	= 'required|exists:menu_items,id|exist_check:menu_items';
			$rules['comment']	= 'required';
		}elseif ($this->method == 'PATCH') {
			$rules['food_id']	= 'required|exists:menu_items,id|exist_check:menu_items';
			$rules['c_id']	= 'required|exists:menu_comment,id|exist_check:menu_comment|condition_check:menu_comment,where:food_id:=:'.$request->food_id;
			$rules['comment']	= 'required';
		}elseif ($this->method == 'PUT') {
			$rules['food_id']	= 'required|exists:menu_items,id|exist_check:menu_items';
			$rules['c_id']	= 'required|exists:menu_comment,id|exist_check:menu_comment|condition_check:menu_comment,where:food_id:=:'.$request->food_id;
		}elseif ($this->method == 'GET') {
			$rules['food_id']	= 'required|exists:menu_items,id|exist_check:menu_items';
			
		}
		$this->validateDatas($request->all(),$rules,$cmessage,$nicenames);	
		if ($this->method == 'PATCH' || $this->method == 'POST') {
			$comment = new Comment;
			$comment->food_id = $request->food_id;

			$foodItem=Menuitems::find($request->food_id);
			$comment->vendor_id = $foodItem->vendor_id;
			$comment->comment = $request->comment;
			$comment->user_id = $auth_id;
		}
		if ($this->method == 'POST') {			
			$comment->save();
			$response['comment_detail']= $comment;
			$message = 'Comment added successfully.It will reflect in chef profile once admin approved';
		} elseif ($this->method == 'PATCH') {
			$comment->c_id = $request->c_id;
			$comment->save();
			$response['comment_detail']= $comment;
			$message = 'Replied successfully.It will reflect in chef profile once admin approved';
		} elseif ($this->method == 'PUT') {
			$check = Commentlike::where('c_id',$request->c_id)->where('user_id',$auth_id)->first();
			if($check){
				Commentlike::where('c_id',$request->c_id)->where('user_id',$auth_id)->delete();
				$response['comment_like_detail']= (object)[];
				$message = 'Unliked successfully';
			}else{
				$comment_like = new Commentlike;
				$comment_like->c_id = $request->c_id;
				$comment_like->user_id = $auth_id;

				$comment_like->save();
				$response['comment_like_detail']= $comment_like;
				$message = 'Comment liked by you';
			}
		} elseif ($this->method == 'GET') {
			$comment	= Comment::root()->where('food_id',$request->food_id)->get();
			$response['comment_detail']= $comment;
			$message	= 'Comment data fetched successfully';
		}
		$response['message'] = $message;
		return \Response::json($response,$status);
	}

	public function userAddress( Request $request)
	{
		// $guard		= ($request->from == 'mobile') ? 'api' : 'web';
		$status		= 200;
		// $auth_id	= auth($guard)->user()->id;

		$user		= $this->authCheck();
		$auth_id	= $user['userId'];
		$userData	= $user['user'];

		$addrDetail	= $rules= array();
		if ($this->method != 'GET' && $this->method != 'DELETE') {
			$rules['address']	= 'required';
			$rules['lat']		= ['required','regex:/^[1-9].+$/'];
			$rules['lang']		= ['required','regex:/^[1-9].+$/'];
			$rules['building']	= 'required';
			$rules['area']		= 'required';
			$rules['pin_code']	= 'required';
			$rules['city']		= 'required';
			$rules['state']		= 'required';
			// $rules['landmark']	= 'required_without:building';
			$rules['address_type']	= 'required';
		}
		if ($this->method == 'PATCH' || $this->method == 'DELETE' || $this->method == 'PUT') {
			$rules['address_id'] = 'required';
		}
		$this->validateDatas($request->all(),$rules,[],[]);

		if ($this->method == 'POST' || $this->method == 'PATCH') {
			$uaddress['user_id']		= $auth_id;
			$uaddress['address_type']	= request('address_type');
			$uaddress['landmark']		= (isset($request->landmark) && $request->landmark !== '') ? request('landmark') : '';
			$uaddress['building']		= (isset($request->building) && $request->building !== '') ? request('building') : '';
			$uaddress['address']		= request('address');
			$uaddress['lat']			= request('lat');
			$uaddress['lang']			= request('lang');
			$uaddress['area']			= request('area');
			$uaddress['pin_code']		= request('pin_code');
			$uaddress['city']			= request('city');
			$uaddress['state']			= request('state');
		}

		if($this->method == 'POST') {
			$uAddr	= Address::where('user_id', $auth_id)->where('address_type',request('address_type'))->where('area',request('area'))->where('pin_code',request('pin_code'))->where('city',request('city'))->where('state',request('state'))->where('address',request('address'))->where('lat',request('lat'))->where('lang',request('lang'))->first();
			if (!empty($uAddr)) {
				$uAddr->fill($uaddress)->save();
				$addrDet= Address::find($uAddr->id);
			} else {
				$addrDet= Address::create($uaddress);
			}
			$addrDetail = Address::where('id',$addrDet->id)->get();
			$message	= "Address data added successfully";
		} elseif ($this->method == 'DELETE') {
			$cookieID	= (request('cookie')) ? request('cookie') : 0;
			$uCartQuery	= uCartQuery($auth_id, $cookieID)->where('address_id',request('address_id'))->first();
			$uAddr		= Address::where("user_id",$auth_id)->where("id",request('address_id'))->delete();
			if(!empty($uCartQuery)) { uCartQuery($auth_id, $cookieID)->update(['address_id'=>0]); }
			$message	= "Address deleted successfully";
		} elseif ($this->method == 'PATCH') {
			Address::where('id',request('address_id'))->update($uaddress);
			$addrDetail	= Address::where('id',request('address_id'))->get();
			$message = 'Address update successfully';
		} elseif ($this->method == 'PUT') {
			$message	= "Address data fetched successfully";
		} else {
			$uAddress	= User::where('id',$auth_id)->with('address')->first();
			$addrDetail	= (!empty($uAddress)) ? $uAddress->address : '' ;
			$message	= "Address data fetched successfully";
		}
		if ($this->method != 'DELETE')
			$response['addressDetail'] = $addrDetail;
		$response['message'] = $message;
		return \Response::json($response,$status);
	}

	public function menuSlotCheck(Request $request)
	{
		$rules['menu_id']	= 'required|numeric|exists:menu_items,id';
		$rules['date']		= 'required|date_format:Y-m-d|after_or_equal:'.date('Y-m-d');

		$menu	= Menuitems::approved()->find($request->menu_id);
		
		if (!empty($menu) && $menu->preparation_time == 'tomorrow') {
			$rules['date']		= 'required|date_format:Y-m-d|after:today';
		}
		$nicenames['menu_id']	= "Menu Identification";
		$this->validateDatas($request->all(),$rules,[],$nicenames);

		$status		= 503;
		$message	= "Menu is not available in our service";
		if (!empty($menu)) {
			$today	= date('Y-m-d');
			$tmmrw	= date("Y-m-d", strtotime("+1 day"));
			$ctime	= date('H:i:s');
			$rtime		= '00:00:00';
			if ($request->date == $tmmrw) {
				$Ctime	= ($today == "2022-03-17") ? '16:00:00' : '18:00:00';
				if (strtotime($ctime) > strtotime($Ctime)) {
					// $rtime	= ($tmmrw == "2022-03-18") ? '18:00:00' : '16:00:00';
				}
			} elseif ($request->date == $today) {
				$todayTime	= date("Y-m-d H:i:s");
				$rtime	= date("H:i:s", strtotime('+2 hours', strtotime($todayTime)));

				if($menu->preparation_time == '1_to_2hrs'){
					$rtime	= date("H:i:s", strtotime('+1 hours', strtotime($todayTime)));
					/*if((date('h:i A',strtotime($todayTime)) >= "09:30 PM" && date('h:i A',strtotime($todayTime)) <= "10:00 PM")){
						$rtime	= date("H:i:s", strtotime($todayTime));
					}*/
				}

			}
			$response['timeSlot']	= timeSlot($rtime);
			$status		= 200;
			$message	= "Menu is available in our service";
		}
		$response['message'] = $message;
		return \Response::json($response,$status);
	}

	public function exploreseemore()
	{
		$response = self::ExploreData();
		$response->push(['name' => 'topRatedChefs', 'slug' => 'topRatedChefs', 'href' => 'seemore'],['name' =>'celebrityChefs','slug' => 'celebrityChefs','href' => 'seemore'],['name' =>'popularChefs','slug' => 'popularChefs','href' => 'seemore']);
		if(request()->from == 'web') {
			$response->push(['name' => 'Chefevents', 'slug' => 'chefevent', 'href' => 'seemore']);
		}
		return $response;
	}

	public function notifyme(Request $request)
	{
		$guard	= ($request->from == 'mobile') ? 'api' : 'web';
		$user	= auth($guard)->user();
		$userId	= (!empty($user)) ? $user->id : 0 ;
		
		if($this->method == "DELETE") {
			$rules['id'] = 'required|numeric';
		} elseif($this->method == "POST") {
			//$rules['user_id'] 		= 'required|numeric';
			$rules['vendor_id'] 	= 'required|numeric';
		}
		$this->validateDatas($request->all(),$rules,[],[]);	
		$response['message'] = "Unprocessable entry."; $status = 422;
		if($user){
			if($this->method == "DELETE"){
				$notify = Notifyme::find($request->id)->delete();
				$response['message'] = "Notify alert removed."; $status = 200;
			} elseif($this->method == "POST") {
				$res = Notifyme::where('user_id',$userId)->where('vendor_id',$request->vendor_id)->first();
				if($res){
					$res->user_id 		= $userId;
					$res->vendor_id 	= $request->vendor_id;
					$res->status 		= 0;
					$res->save();
				} else {
					$data = ['user_id' => $userId,'vendor_id' => $request->vendor_id];
					$res = Notifyme::create($data);
				}
				if(!empty($res)){
					$response['id']      = $res->id;
					$response['message'] = "Noted! Will notify you once Chef is back online to accept orders.";
					$status              = 200;
				}
			}
		} else {
			$response['message'] = "Please Login"; $status = 401;
		}
		return \Response::json($response,$status);
	}

	public function userReferral(Request $request)
	{
		$guard = ($request->from == "mobile") ? "api" : "web";
		$user  = auth($guard)->user();
		$ref_settings = Referralsettings::find(1,['referral_user_credit_amount','referral_user_orders_count','referer_user_credit_amount','referral_share_description']);
		$response['referral_code'] = $user->referal_code;
		$response['share_description'] = $ref_settings->referral_share_description.' '.$user->referal_code;
		$response['referral_info'] = "Invite friends to Knosh and get ₹".$ref_settings->referer_user_credit_amount." for each ".$ref_settings->referral_user_orders_count. " order of your friends. Also they will get ₹".$ref_settings->referral_user_credit_amount." while register with your code.";
		return \Response::json($response,200); 
	}

	public function userWallet(Request $request) 
	{
		$guard  = ($request->from == "mobile") ? "api" : "web";
		$user   = auth($guard)->user();
		$response['wallet_history'] = WalletHistory::where('user_id',$user->id)->get();
		$response['wallet_amount'] = $user->wallet;
		return \Response::json($response,200);	
	}
}
?>