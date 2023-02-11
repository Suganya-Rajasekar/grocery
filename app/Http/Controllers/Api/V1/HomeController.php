<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
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
}

?>