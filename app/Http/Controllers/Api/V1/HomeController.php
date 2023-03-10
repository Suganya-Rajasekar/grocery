<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Restaurants;
use App\Models\Menuitems;

class HomeController extends Controller
{
	public function homepage(Request $request)
	{
		$banner = Banner::active()->get();
		$res_det = Restaurants::where('id',1)->first();
		$res_cat = json_decode($res_det->category_list);
		$shop_category = $res_cat;
		$id = [];
		foreach ($res_cat as $key => $value) {
			$id[] = array_push($id, $value->cate_id);
		}
		$list_ids = implode(',', array_unique($id));
		$category = Category::select('id','name','res_id','avatar','p_id')/*->where('res_id','0')*/->where('visibility_mode','on')->whereIn('id',array($list_ids))->where('p_id','0')->get()->map(function ($result) {
			$result->append('subcategory');
			return $result;
		});
		$response['banner']				= $banner;
		$response['category']			= $category;
		$response['shop_by_category']	= $shop_category;
		return \Response::json($response,200);
	}

	public function categoryList(Request $request)
	{
		$pageNumber = isset($request->pageNumber) ? $request->pageNumber: 1;
		$cat = Category::get()->append('subcategory')->paginate(5, ['*'], 'page', $pageNumber);
		$response['categoryList'] = $cat;
		return \Response::json($response,200);
	}

	public function searchProducts(Request $request)
	{
		$items = Menuitems::select('*')->where('restaurant_id','0');
		if (isset($request->cat_id) && isset($request->sub_cat_id)) {
			$items = $items->where('main_category',$request->cat_id)->where('sub_category',$request->sub_cat_id);
		}elseif (isset($request->cat_id)) {
			$items = $items->where('main_category',$request->cat_id);
		}elseif(isset($request->sub_cat_id)){
			$items = $items->where('sub_category',$request->sub_cat_id);
		}
		$items = $items->get();
		$response['menuitems'] = $items;
		return \Response::json($response,200);	
	}
}

?>