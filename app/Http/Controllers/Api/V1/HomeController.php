<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Menuitems;
class HomeController extends Controller
{

	public function homepage(Request $request)
	{
		$banner = Banner::active()->get();
		$shop_category = Category::select('id','name','res_id','avatar')->where('res_id','0')->get();
		$category = Category::select('id','name','res_id','avatar','p_id')/*->where('res_id','0')*/->where('visibility_mode','on')->where('p_id','0')->get()->map(function ($result) {
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

	public function search(Request $request)
	{
		$items = Menuitems::select('*')->where('restaurant_id','0');
		if (isset($request->cat_id)) {
			$items = $items->where('main_category',$request->cat_id);
		}
		$cat = Category::where('res_id','0')->get()->append('subcategory');
		$items = $items->get();
		$response['menuitems'] = $items;
		$response['category'] = $cat;
		return \Response::json($response,200);	
	}
}

?>