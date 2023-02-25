<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\Testimonials;
use App\Models\Category;
use App\Models\Service;
use App\Models\Addon;
use App\Models\Time;
use App\Models\Book;
use App\Models\SiteSetting;
use App\Models\subscription_plans as SubscriptionPlans;
use App\Models\User;
use App\Models\Homecontent;
use App\Models\Mediapress;
use App\Models\Page;
use Illuminate\Http\Response;
use Cookie, Session;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{
	/**
	* Show the application dashboard.
	*
	* @return \Illuminate\Contracts\Support\Renderable
	*/
/*	public function index(Request $request)
	{
		$lat	= 9.9239637;
		$lang	= 78.1222102;
		if (isset($request->latitude))
			$lat = $request->latitude;
		if (isset($request->longitude))
			$lang = $request->longitude;

		$homePage	= $this->homePageData($lat, $lang,'','knoshWorld');
		$banner		= array();
		if($homePage && !empty($homePage->banner)){
			$banner	= $homePage->banner;
		}
		$homecontent = Homecontent::where('id', 1)->first();
		$mediapress  = Mediapress::get();
		$topRatedChefs = $homePage->topRatedChefs; 
		return view('main.main',compact('banner','homecontent','mediapress','topRatedChefs','homePage'));
	}*/

	public function index( Request $request)
	{
		if (!\Auth::check()) {
			return \Redirect::to('login') ;
		}

		$lat	= 9.9239637;
		$lang	= 78.1222102;
		if (isset($request->latitude))
			$lat = $request->latitude;
		if (isset($request->longitude))
			$lang = $request->longitude;

		$homePage = $this->homePageData($lat, $lang);

		$celebrityChefs=array();
		if($homePage && !empty($homePage->celebrityChefs)){
			$celebrityChefs=$homePage->celebrityChefs;
		}

		$popularChefs=array();
		if($homePage && !empty($homePage->popularChefs)){
			$popularChefs=$homePage->popularChefs;
		}

		$topRatedChefs=array();
		if($homePage && !empty($homePage->topRatedChefs)){
			$topRatedChefs=$homePage->topRatedChefs;
		}

		$nearByChefs=array();
		if($homePage && !empty($homePage->nearByChefs)){
			$nearByChefs=$homePage->nearByChefs;
		}

		$popularNearYou=array();
		if($homePage && !empty($homePage->popularNearYou)){
			$popularNearYou=$homePage->popularNearYou;
		}

		$explore=array();
		if($homePage && !empty($homePage->explore)){
			$explore=$homePage->explore;
		}

		$popularRecipe=array();
		if($homePage && !empty($homePage->popularRecipe)){
			$popularRecipe=$homePage->popularRecipe;
		}

		$chefevent = array();
		if($homePage && !empty($homePage->chefevent)){
			$chefevent = $homePage->chefevent;
		}

		$home_event = array();
		if($homePage && !empty($homePage->homeevent)){
			$home_event = $homePage->homeevent;
		}

		$blogs=array();
		if($homePage && !empty($homePage->blogs)){
			$blogs=$homePage->blogs;
		}
		$whatsTrending=array();
		if($homePage && !empty($homePage->whatsTrending)){
			$whatsTrending=$homePage->whatsTrending;
		}
		$banner		= array();
		if($homePage && !empty($homePage->banner)){
			$banner	= $homePage->banner;
		}
		if(isset($request->mode) && $request->mode == 'ajax'){
			$homePage = $this->homePageData($lat, $lang);
			return view('main.nearbychef',compact('nearByChefs','topRatedChefs','celebrityChefs','popularChefs','popularNearYou'));
		}
		if($request->ajax() && $request->call == "onscroll") {
			if($request->seemore == "celebrityChefs") {
				$response['lastpage']  = $celebrityChefs->last_page;
				$response['totaldata'] = $celebrityChefs->total;
				$response['html'] 	  = (string) view('main.nearbychef',compact('celebrityChefs'));
			} elseif ($request->seemore == "nearByChefs") {
				$response['lastpage'] = $nearByChefs->last_page;
				$response['totaldata'] = $nearByChefs->total;
				$response['html']     = (string) view('main.nearbychef',compact('nearByChefs')); 
			} elseif ($request->seemore == "") {
				$response['html'] = (string) view('main.nearbychef',compact('popularRecipe','blogs','whatsTrending','homePage')); 
			} elseif($request->seemore == "chefevent") {
				$response['lastpage'] = $chefevent->last_page;
				$response['totaldata'] = $chefevent->total;
				$response['html'] 	  = (string) view('main.nearbychef',compact('chefevent')); 
			} elseif($request->seemore == "homeevent") {
				$response['lastpage'] = $home_event->last_page;
				$response['totaldata'] = $home_event->total;
				$response['html'] 	  = (string) view('main.nearbychef',compact('home_event')); 
			}
			return $response; 
		}
		$homecontent = Homecontent::where('id', 1)->first();
		$mediapress  = Mediapress::get();
		return view('main.main',compact('homePage','explore','celebrityChefs','popularChefs','topRatedChefs','popularNearYou','popularRecipe','blogs','whatsTrending','banner','homecontent','mediapress'));
	}

/*	public function knoshWorld( Request $request)
	{
		$lat	= 9.9239637;
		$lang	= 78.1222102;
		if (isset($request->latitude))
			$lat = $request->latitude;
		if (isset($request->longitude))
			$lang = $request->longitude;

		$homePage = $this->homePageData($lat, $lang,'','knoshWorld');

		$celebrityChefs=array();
		if($homePage && !empty($homePage->celebrityChefs)){
			$celebrityChefs=$homePage->celebrityChefs;
		}

		$popularChefs=array();
		if($homePage && !empty($homePage->popularChefs)){
			$popularChefs=$homePage->popularChefs;
		}

		$topRatedChefs=array();
		if($homePage && !empty($homePage->topRatedChefs)){
			$topRatedChefs=$homePage->topRatedChefs;
		}

		$nearByChefs=array();
		if($homePage && !empty($homePage->nearByChefs)){
			$nearByChefs=$homePage->nearByChefs;
		}

		$popularNearYou=array();
		if($homePage && !empty($homePage->popularNearYou)){
			$popularNearYou=$homePage->popularNearYou;
		}

		$explore=array();
		if($homePage && !empty($homePage->explore)){
			$explore=$homePage->explore;
		}

		$popularRecipe=array();
		if($homePage && !empty($homePage->popularRecipe)){
			$popularRecipe=$homePage->popularRecipe;
		}

		$chefevent = array();
		if($homePage && !empty($homePage->chefevent)){
			$chefevent = $homePage->chefevent;
		}

		$blogs=array();
		if($homePage && !empty($homePage->blogs)){
			$blogs=$homePage->blogs;
		}
		$whatsTrending=array();
		if($homePage && !empty($homePage->whatsTrending)){
			$whatsTrending=$homePage->whatsTrending;
		}
		if(isset($request->mode) && $request->mode == 'ajax'){
			$homePage = $this->homePageData($lat, $lang);
			return view('main.nearbychef',compact('nearByChefs','topRatedChefs','celebrityChefs','popularChefs','popularNearYou'));
		}
		if($request->ajax() && $request->call == "onscroll") {
			if($request->seemore == "celebrityChefs") {
				return view('main.nearbychef',compact('celebrityChefs')); 
			} elseif ($request->seemore == "nearByChefs") {
				return view('main.nearbychef',compact('nearByChefs')); 
			} elseif ($request->seemore == "") {
				return view('main.nearbychef',compact('popularRecipe','blogs','whatsTrending','homePage')); 
			} elseif($request->seemore == "chefevent") {
				return view('main.nearbychef',compact('chefevent')); 
			}
		}
		return view('main.knosh_world',compact('homePage','explore','celebrityChefs','popularChefs','topRatedChefs','popularNearYou','popularRecipe','blogs','whatsTrending'));
	}*/

	public function homePageData($lat, $lang, $explore='',$func = '')
	{
		$arr = ['latitude'=>$lat,'longitude'=>$lang];
		/*if($func == 'knoshWorld'){
			$arr['banner_without'] = true;
			if($explore != '' ) $arr['explore'] = 'exploreData';
		} else {
			$arr['frompage'] = 'webhomepage';
		}*/
		request()->merge($arr);
		return app()->call('App\Http\Controllers\Api\Customer\CustomerController@homepage')->getData();
	}

	public function chefProfile($id)
	{
		$chefProfile =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@chefinfo',[
			'request' => request()->merge(['user_id'=>\Auth::id(), 'id'=>$id])
		])->getData();
		if ($chefProfile->status == 200) {
			$chefinfo = (object) $chefProfile->chefinfo;
			$timeslot =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@TableDatasAll',[
				'request' => request()->merge(['requestdata'=>'TimeSlot'])
			])->getData();
			$timeslots = (object) $timeslot->timeslot; 
			return view('home.chefprofile',compact('chefinfo','timeslots'));
		} else {
			return \Redirect::to('');
		}
	}

	public function showSeeMore($module, $lat = 0 , $lang = 0)
	{
		$rules = [];
		request()->merge(['seemore'=>trim($module),'latitude'=>$lat,'longitude'=>$lang,'pageNumber' => isset(request()->page) ? request()->page : 1]);
		/*$rules['latitude']  = ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'];
		$rules['longitude'] = ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'];*/
		if (isset(request()->seemore) && request()->seemore != '') {
			$rules['seemore']   = ['required','in:nearByChefs,celebrityChefs,popularChefs,popularNearYou,topRatedChefs,chefevent'];
		}
		$response = $this->validateDatas(request()->all(),$rules,[],[],'web');
		if (!empty($response)) {
			// \Flash::error($response['message']);
			// request()->flash();
			//Session::flash('error', $response['message']);
			 \Session::put(['error' => $response['message']]); 
			return \Redirect::to('continueredirect/popscroll')->withErrors($response['validator'])->withInput();
		} else {

			$alloffer    =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@Offers',[
				'request' => request()->merge(['latitude' => $lat, 'longitude' => $lang ])
			])->getData();

			$seeMoreDetails =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@homepage')->getData();

			$Explore = $seeMoreDetails;
			if (!empty($seeMoreDetails->$module->data)) {
				$seemore =  $seeMoreDetails->$module->data;
				if($module == 'popularNearYou'){
					return view('home.popularnear',compact('seemore','alloffer','Explore'),compact('module'));
				} else { 
					$last_page =  $seeMoreDetails->$module->last_page; 
					$current_page =  $seeMoreDetails->$module->current_page; 
					if(!(\Request::has('page'))) {
						return view('home.seemore',compact('seemore','alloffer','last_page','current_page','Explore','module'));
					}
					$response['html'] = (String) view('home.seemore_page',compact('seemore','alloffer','last_page','current_page'),compact('module'));
					return \Response::json($response,200);
				}
			} else {
				// \Flash::error("No datas");
				// request()->flash();
				if($module == 'celebrityChefs'){
					$msg = 'Celebrity chefs not available in your location';
				} elseif($module == 'popularChefs'){
					$msg = 'Popular chefs not available in your location'; 
				} elseif($module == 'popularNearYou'){
					$msg = 'Popular dishes not available in your location'; 
				} elseif($module == 'nearByChefs'){
					$msg = 'NearBy chefs not available in your location'; 
				} elseif($module == 'topRatedChefs'){
					$msg =' Toprated chefs not available in your location'; 
				} else {
					$msg = 'No datas'; 
				}
				Session::forget('error');
				/*Session::flash('error', $msg);  */    
				\Session::put(['error' => $msg]);         
				return \Redirect::to('continueredirect/popscroll')->withErrors("No datas")->withInput();
			}
		}
	}

	public function showexplore($keyword, $lat = 0, $lang = 0)
	{
		request()->merge(['keyword'=> $keyword,'latitude' => $lat, 'longitude' => $lang ]);
		$rules['keyword']   = 'required';
		$rules['latitude']  = ['required'/*,'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'*/];
		$rules['longitude'] = ['required'/*,'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'*/];
		$response   = $this->validateDatas(request()->all(),$rules,[],[],'web');
		if (!empty($response)) {
			/*\Flash::error($response['message']);
			request()->flash();*/
			//Session::flash('error', $response['message']);
			\Session::put([
				'error' => $response['message']
				]); 
			return \Redirect::to('continueredirect/popscroll')->withErrors($response['validator'])->withInput();
		} else {
			$res=app()->call('App\Http\Controllers\Api\Customer\CustomerController@explore');
			if($res->status() == 200)   
			{
				$explore    =  $res->getData();
			} else {
				\Flash::error("No datas");
				request()->flash();
				return \Redirect::to('continueredirect/popscroll')->withErrors("No datas")->withInput();
			}
			$module     = $keyword;
			$alloffer    =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@Offers')->getData();
			if ($keyword == 'cuisines') { 
				$cuisine    =  $explore->aData;
				$cuisineDe  =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@explore',[
				'request' => request()->merge(['keyword'=> $keyword,'latitude' => $lat, 'longitude' => $lang, 'cuisine_id'=> $cuisine[0]->id ])
				])->getData();
				$Explore = $this->homePageData($lat,$lang,'explore');

				if (!empty($cuisineDe->aData->data)) {
					$seemore =  $cuisineDe->aData->data;
					return view('home.cuisines',compact('cuisine','seemore','alloffer','Explore'),compact('module'));
				} else {
					/*\Flash::error("No datas");
					request()->flash();*/
					Session::forget('error');
					//Session::flash('error', 'Cuisine not available in your location');
					\Session::put([
					'error' => 'Cuisine not available in your location'
					]); 
					return \Redirect::to('continueredirect/popscroll')->withErrors("No datas")->withInput();
				}
			} else {
				if (!empty($explore->aData->data)) {
						$seemore        =  $explore->aData->data;
						$last_page      =  $explore->aData->last_page; 
						$current_page   =  $explore->aData->current_page;
						$Explore = $this->homePageData($lat,$lang,'explore');
						if($keyword == 'popular'){
							return view('home.popularnear',compact('seemore','alloffer','current_page','last_page','Explore'),compact('module'));
						}
						if(!(\Request::has('page'))) {
						return view('home.seemore',compact('seemore','alloffer','current_page','last_page','Explore'),compact('module'));
						}
						$response['html'] = (String) view('home.seemore_page',compact('seemore','alloffer','last_page','current_page'),compact('module'));
						return \Response::json($response,200);
		
						
				} else {
					/*\Flash::error("No datas");
					request()->flash();*/
					if($module == '7'){
						$msg = 'Snacks not available in your location';
					} elseif($module == '8'){
						$msg = 'Dessert not available in your location'; 
					} elseif($module == '9'){
						$msg = 'Bakery not available in your location'; 
					} else {
						$msg = 'No datas'; 
					}
					Session::forget('error');
					//Session::flash('error', $msg);
					\Session::put([
					'error' => $msg
					]);
					return \Redirect::to('continueredirect/popscroll')->withErrors("No datas")->withInput();
				}
			}
		}
	}

	public function showCuisineChef($keyword, $cuisineid, $lat=0, $lang=0)
	{
		request()->merge(['keyword'=> $keyword,'cuisine_id'=> $cuisineid,'latitude' => $lat, 'longitude' => $lang ]);
		$rules['keyword']   = 'required';
		// $rules['latitude']  = ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'];
		// $rules['longitude'] = ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'];
		$response   = $this->validateDatas(request()->all(),$rules,[],[],'web');
		if (!empty($response)) {
			/*\Flash::error($response['message']);
			request()->flash();*/
			//Session::flash('error', $response['message']);
			\Session::put([
				'error' => $response['message']
				]); 
			return \Redirect::to('')->withErrors($response['validator'])->withInput();
		} else {
			$alloffer     =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@Offers',[
				'request' => request()->merge(['latitude' => $lat, 'longitude' => $lang ])
				])->getData();

			$exploreDetails  =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@explore',[
				'request'    => request()->merge(['keyword'=> $keyword,'latitude' => $lat, 'longitude' => $lang ])
				])->getData();
			$cuisine         =  $exploreDetails->aData; 
			$cuisineDetails  =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@explore',[
			'request' => request()->merge(['keyword'=> $keyword,'latitude' => $lat, 'longitude' => $lang, 'cuisine_id'=> $cuisineid ])
			])->getData();
			if(count( (array)$cuisineDetails->aData ) > 0) {
				$seemore   = $cuisineDetails->aData->data;
			} else{
				$seemore   = $cuisineDetails->aData;
				/*\Flash::error("No datas");
				request()->flash();*/
				Session::forget('error');
				//Session::flash('error', 'No datas');
				\Session::put([
				'error' => 'No datas'
				]);
				return \Redirect::back()->withErrors("No datas")->withInput();
			}
				$module=$keyword;
				$last_page =  $cuisineDetails->aData->last_page; 
				$current_page =  $cuisineDetails->aData->current_page; 

				if(!(\Request::has('pageNumber'))) {
					return view('home.cuisine-detail',compact('seemore','alloffer','cuisine','last_page','current_page'),compact('module'));
				}
				// print_r($last_page);print_r($current_page); exit();
				 $response['html'] = (String) view('home.cuisine_page',compact('seemore','alloffer','cuisine','last_page','current_page'),compact('module'));
				 return \Response::json($response,200);

		}  
	}

	public function showOfferChef($offerid, $lat, $lang)
	{
		request()->merge(['id'=> $offerid,'latitude' => $lat, 'longitude' => $lang ]);
		$rules['id']   = 'required';
		$rules['latitude']  = ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'];
		$rules['longitude'] = ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'];
		$response   = $this->validateDatas(request()->all(),$rules,[],[],'web');
		if (!empty($response)) {
			/*\Flash::error($response['message']);
			request()->flash();*/
			//Session::flash('error', $response['message']);
			\Session::put([
					'error' => $response['message']
					]);
			return \Redirect::back()->withErrors($response['validator'])->withInput();
		} else {
		$alloffer    =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@Offers',[
			'request' => request()->merge(['latitude' => $lat, 'longitude' => $lang ])
		])->getData();

		$offerchef =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@Offers',[
			'request' => request()->merge(['latitude' => $lat, 'longitude' => $lang, 'id'=> $offerid ])
		])->getData();
	
		if(count( (array)$offerchef->chefs ) > 0)
		{
		  $seemore = $offerchef->chefs->data;
		} else{
		   $seemore = $offerchef->chefs;
			Session::forget('error');
			//Session::flash('error', 'No datas');
			\Session::put([
					'error' => 'No datas'
					]);
			return \Redirect::back()->withErrors("No datas")->withInput();
		}
		//echo "<pre>";print_r($offerchef);exit;
		return view('home.offerchef',compact('alloffer','seemore'));   
		} 
	}

	public function showsearch()
	{
		return view('frontend.searchchef');
	}

	public function searchChefResult(Request $request)
	{
		$latitude     = $request->lat;
		$longitude    = $request->lang;
		$keyword      = $request->q;
		$searchresult =  app()->call('App\Http\Controllers\Api\Customer\CustomerController@search',[
			'request' => request()->merge(['keyword' => $keyword,'latitude' => $latitude,'longitude' => $longitude])
		])->getData();
		$search = (object) $searchresult->result;
		$timeslot =  app()->call('App\Http\Controllers\Api\Emperica\EmpericaController@TableDatasAll',[
			'request' => request()->merge(['requestdata'=>'TimeSlot'])
		])->getData();
		$timeslots = (object) $timeslot->timeslot;

		$Response['html'] = (string)view('frontend.searchchefview',compact('search','timeslots'));
		return $Response;
	}

	public function showpage(Request $request, $slug,$source="web")
	{ 
		// var_dump(request()->expectsJson());exit;
		$page = Page::where('slug', '=', $slug)->first();
		if (!empty($page)) {


			if ( isset($request->source) && $request->source == 'api' )
				$source = $request->source;
			
			return view('page',compact('page','source'));
		} else {
			return view('404');
		}
	}

	public function showcontactpage()
	{
		return view('pages.contact-us');
	}

	public function showblogpage()
	{
		$mediapress  = Mediapress::get();
		return view('pages.blogs',compact('mediapress'));
	}

	public function setsessionlatlang(Request $request)
	{
		if (isset($request->lat))
			$lat = $request->lat;
		if (isset($request->lang))
			$lang = $request->lang;
		\Session::put('latitude',$lat);
		\Session::put('longitude',$lang);
	}

	public function showpopular(Request $request)
	{
		$id    = $request->id;
		$menuinfos  = app()->call('App\Http\Controllers\Api\Customer\CustomerController@getPopularRecipe',[
			'request' => request()->merge(['id'=>$id])
		])->getData();
		if (isset($menuinfos->popularRecipe)) {
			$menuinfo           = (object) $menuinfos->popularRecipe;
			$Response['html']   = (string)view('frontend.popularmodel',compact('menuinfo'));
			return $Response;
		} else {
			Flash::success($menuinfos->message);
			return \Redirect::to('');
		}
	}

	public function showblog(Request $request)
	{
		$id    = $request->id;
		$menuinfos  = app()->call('App\Http\Controllers\Api\Customer\CustomerController@getBlog',[
			'request' => request()->merge(['id'=>$id])
		])->getData();
		if (isset($menuinfos->blog)) {
			$menuinfo           = (object) $menuinfos->blog;
			$Response['html']   = (string)view('frontend.blogmodel',compact('menuinfo'));
			return $Response;
		} else {
			Flash::success($menuinfos->message);
			return \Redirect::to('');
		}
	}

	public function DownloadApp(Request $request)
	{
		return view('main.mobileappinfo');
	}
}