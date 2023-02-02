<?php
namespace App\Imports;
use Illuminate\Http\Request;
use App\Models\Menuitems;
use App\Models\Restaurants;
use App\Models\Addon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers;
use Exception;
use http\Exception\InvalidArgumentException;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;


class MenuitemImport implements ToModel , WithValidation, WithHeadingRow
{
            use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   
   
    public function model(array $row)
    {
            $v_id = isset($_REQUEST['vendor_id']) ? $_REQUEST['vendor_id'] : \Auth::user()->id;
        
            $user = DB::table('categories')->where('name', $row['main_category'])->first();
            $categoryid = $user->id;
            if($row['addons']) {
                $addons_exp = explode(',',$row['addons']);
                $addon  = Addon::where('user_id',$_REQUEST['vendor_id'])->where('type','addon')->whereIn('name',$addons_exp)->pluck('id')->toArray();
                $addons_imp = implode(',', $addon);
            }
            if($row['variants']){
                $var = explode(',',$row['variants']); 
                foreach ($var as $key => $val) {
                    $var_exp                = explode(':', $val);
                    $variant_id             = Addon::where('type','unit')->where('user_id',$_REQUEST['vendor_id'])->where('name',$var_exp[0])->pluck('id')->toArray(); 
                    $var_exp['unit']        = (string) $variant_id[0];
                    $var_exp['price_unit']  = $var_exp[1];
                    unset($var_exp[0],$var_exp[1]);
                    $unit[]                 = $var_exp['unit'];
                    $price_unit[]           = $var_exp['price_unit'];
                    $variants['unit']       = $unit;
                    $variants['price_unit'] = $price_unit;
                }
            }
            $addons     = (isset($addons_imp) && !empty($addons_imp)) ? $addons_imp : '';
            $variants   = (isset($variants) && !empty($variants)) ? $variants : '';
            $restaurant = Restaurants::select('id')->where('vendor_id',$v_id)->first();
            $menuitem   = Menuitems::where('vendor_id',$v_id)->where('name',$row['name'])->first();
            if(!empty($menuitem)){
                $menuitem->description      = $row['description'];
                $menuitem->price            = $row['price'];
                $menuitem->preparation_time = $row['preparation_time'];
                $menuitem->item_type        = $row['item_type'];
                $menuitem->main_category    = $categoryid;
                $menuitem->stock_status     = $row['stock_status'];
                if($row['stock_status'] == 'instock'){
                $menuitem->quantity         = $row['quantity'];
                }
                $menuitem->addons           = $addons;
                $menuitem->unit             = $variants;
                $menuitem->discount         = $row['discount'];
                $menuitem->status           = $row['status'];
                $menuitem->save();
            } else {
                $arr = array('restaurant_id' => $restaurant->id,'vendor_id'=> $v_id,'name'     => $row['name'],'description' =>$row['description'],'price' => $row['price'],'preparation_time' => $row['preparation_time'],'item_type' => $row['item_type'],'main_category' => $categoryid,'stock_status' =>$row['stock_status'],'addons' => $addons,'unit' => $variants,'discount' => $row['discount'],'status' => $row['status']);
                if($row['stock_status'] == 'instock'){
                    $arr['quantity'] = $row['quantity'];
                }
                return new Menuitems($arr);              
       }

       
    }

    public function rules(): array
    {
        return [
             'main_category' =>  'required',
             'main_category' =>  function($attribute, $value, $onFailure){
                       $users = DB::table('categories')->get();
                        $category = [];
                        foreach ($users as $user) {
                            $category[] = $user->name;
                         }  
                     if(!in_array($value,$category,TRUE)){
                    $onFailure('category type name is wrong');              
                      } 
                 },   
               'name' => 'required',
               'description' =>'required',
               'preparation_time' => 'required',
               'preparation_time' => function($attribute, $value, $onFailure) {
                        $preparation = array('1_to_2hrs','2_to_3hrs','tomorrow');
                  if(!in_array($value,$preparation,TRUE)){                
                       $onFailure($value.'is invalid preparation_time');
                       
                  }
                  $res_preparation = ($value == '1_to_2hrs' || $value == '2_to_3hrs') ? 'ondemand' : 'preorder';
                  $chef_preparation = Restaurants::where('vendor_id',$_REQUEST['vendor_id'])->where('preparation_time',$res_preparation)->exists();
                  if(!$chef_preparation){
                        $onFailure($value.'preparation time is not valid for your restaurant');
                  }
                 },
                'price' => 'required|numeric',
                  'item_type' => function($attribute, $value, $onFailure) {
                        $foodtype = array('veg','nonveg');
                  if(!in_array($value,$foodtype,TRUE)){
                       $onFailure('item_type is wrong');
                  }
                 },
                'addons' => function($attribute, $value, $onFailure) {
                    if(!is_null($value)){
                        $value = explode(',', $value);
                        $addon = Addon::where('type','addon')->where('user_id',$_REQUEST['vendor_id'])->whereIn('name',$value)->exists();
                        if(!$addon){
                            $onFailure('given addons is not available');
                        }
                    }
                  },
                'variants' => function($attribute, $value, $onFailure) {
                    if(!is_null($value)){
                        $value = explode(',',$value); 
                        foreach ($value as $key => $val) {
                        $var_exp = explode(':', $val);
                        $units[] = $var_exp[0]; 
                        }
                        $variant = Addon::where('type','unit')->where('user_id',$_REQUEST['vendor_id'])->whereIn('name',$units)->exists();
                        if(!$variant){
                            $onFailure('given variants is not available');
                        }
                    }
                  },
                'stock_status' => function($attribute, $value, $onFailure) {
                 $preparation = array('instock','outofstock');
                  if(!in_array($value,$preparation,TRUE)){
                       $onFailure('stock_status is wrong');
                    }
                  }, 
                'quantity' => 'required_if:stock_status,instock',   
                'discount' => function($attribute, $value, $onFailure) {
                       if(!is_null($value) && $value < 0){
                        $onFailure('Discount must be positive');    
                       } 
                  },
                'status' => 'required|in:approved,pending,deleted',         
        ];
    }
}


