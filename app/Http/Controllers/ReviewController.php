<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usermanage as User;
use Illuminate\Support\Facades\Hash;
use Flash;
use App\Models\Review;
use App\Models\Customer;
use App\Models\Orderdetail;
use App\Models\Chefs;
use App\Exports\ReviewExport;

class ReviewController extends Controller
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
		$filter = $request->query('filter');
		$customerData   = Customer::orwhere('role',1)->get();
		$vendorData     = Chefs::approved()->Haveinfo()->havemenus()->get();
		$vendor_id = $request->query('vendor_id')  ? $request->query('vendor_id') : '';
		$rating   = $request->query('rating') ? $request->query('rating') : '';
		//$status   = $request->query('status') ? $request->query('status') : '';
		$date    = $request->query('date') ? $request->query('date') : '';
		$search    = $request->query('search') ? $request->query('search') : '';
		$pageCount=10;
		$page=(($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
		$role=\Auth::user()->role;
		$authid=\Auth::user()->id;
		$resultData = new Review;
		if ($request->query('user_id') != '') {
			$resultData = $resultData->where('user_id',$request->query('user_id'));
		}
		if ($request->query('vendor_id') != '') {
			$resultData = $resultData->where('vendor_id',$request->query('vendor_id'));
		}
		if ($request->query('rating') != '') {
			$resultData = $resultData->where('rating',$request->query('rating'));
		}
		if ($request->query('status') != '') {
			$resultData = $resultData->where('status',$request->query('status'));
		}
		if ($request->query('search') != ''){
			$resultData = $resultData->Where('reviews', 'like', '%'.$search.'%')->orWhereHas('order', function($query) use($search) {
				$query->where('s_id', 'like', '%'.$search.'%');
			});
		}
		if ($request->query('date') != '') {
			$sDate  = explode(" - ",$request->date);
			$resultData    = $resultData->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
		} 
		
		$resultData = $resultData->orderByDesc('id')->paginate($pageCount);
		// echo "<pre>";
		// print_r(\DB::getQueryLog($resultData));die();
		return view('review.index',compact('resultData','page','filter', 'customerData', 'vendorData'));
	}

	public function create()
	{
		$review = [];
		$orders = Orderdetail::select('id','vendor_id','res_id','user_id','s_id')->checkifreview()->completed()->get();
		$vendorData     = Chefs::Haveinfo()->havemenus()->get();
		return view('review.form',compact('review','orders','vendorData'));
	}

	public function edit(Request $request, $id)
	{
		$review	= Review::find($id);
		if($request->action == 'reply_check'){
			$review	= Review::where('r_id',$id)->first();
		}
		if($request->ajax()) {
			return \Response::json($review);
		}
		return view('review.form',compact('review'));
	}

	public function update(Request $request)
	{
        if ($request->id > 0 || $request->review_id > 0) {
            $review	= Review::find($request->id);
            $order  = Review::find($request->review_id,['id','vendor_id','user_id','created_by']);
            if (!empty($order)) {
 	           	if($order->created_by == 0) {
            		Flash::error('You are not reply for this review');
            		return redirect(getRoleName().'/review');
            	} else {
            		request()->merge(['from'=>'web','vendor_id'=>$order->vendor_id,'user_id'=>$order->user_id]);
            		$chefreviews    = app()->call('App\Http\Controllers\Api\Order\OrderController@reviews');
            		$data           = $chefreviews->getData();
            		if ($chefreviews->status() == 422) {
            			Flash::error($data->message);
            			$request->flash();
            			return redirect(getRoleName().'/review');
            		}
            		if(isset($request->reply)) {
            			Flash::success('Reply updated successfully.');
            		} else {
            			Flash::success('Review reply successfully.');
            		}
            		return redirect(getRoleName().'/review');   
            	}
            }
           if(empty($review)) {
				Flash::error('Invalid review detail submitted');
				return redirect(getRoleName().'/review');
			}
			$review->reviews	= $request->reviews;
			$review->rating		= $request->rating;
			$review->status		= $request->status;
			$review->reason		= isset($request->reason) ? $request->reason : '';
			$review->save();
			$message	= 'Review details updated successfully.';
		} else {
			if ($request->order_id == 0) {
				$check_admin = Review::where('vendor_id',$request->chef_id)->where('created_by',0)->exists();
				if(!$check_admin) {	
					$request->replace($request->except(['order_id']));	
				} else {
					Flash::error('Role Admin already review this chef.');
					return redirect(getRoleName().'/review/create');
				}
			}
			$review	= new Review;
			if($request->order_id){
				$order	= Orderdetail::find($request->order_id,['id','vendor_id','user_id']);
				if (empty($order)) {
					Flash::error('Invalid review detail submitted');
					return redirect(getRoleName().'/review');
				}
				request()->merge(['from'=>'web','vendor_id'=>$order->vendor_id,'user_id'=>$order->user_id,'_method' => 'POST']);
			} elseif($request->chef_id && !isset($request->order_id)) {
				request()->merge(['from'=>'web','user_id'=>\Auth::user()->id,'_method' => 'POST']);	
			}
			$chefreviews	= app()->call('App\Http\Controllers\Api\Order\OrderController@reviews');
			$data			= $chefreviews->getData();
			if ($chefreviews->status() == 422) {
				Flash::error($data->message);
				$request->flash();
				return redirect(getRoleName().'/review');
			}
            $message    = 'Review details saved successfully.';
		}
		Flash::success($message);
		return redirect(getRoleName().'/review');
	}

	public function destroy($id)
	{
		$result =  Review::find($id);
		if($result){
			$result = $result->delete();
			if ($result) {
				Flash::success('Review detail is deleted.');
			}
		}else{
			Flash::success('Please Refresh Your Page...');
		}
		return redirect(getRoleName().'/review');
	}

	public function reviewexport(Request $request,$slug)
	{
		$request->all();
		$exporter = app()->makeWith(ReviewExport::class, compact('request'));  
		return $exporter->download('ReviewExport_'.date('Y-m-d').'.'.$slug);
	}

}
