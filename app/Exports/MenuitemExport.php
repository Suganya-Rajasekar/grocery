<?php
   
namespace App\Exports;

   
use App\Models\Menuitems;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class MenuitemExport implements FromView, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

   public function __construct($request)
    {
        $this->request = $request;
    }
    
     public function headings(): array
    {
        return [
            'name',
            'description',
            'price',
            'preparation_time',
            'main_category',
            'stock_status',
            'quantity',
            'addons',
            'variants',
            'discount',
            'status',
        ];
    }
    
    public function view(): View
    {
        $search     = $this->request->search ?? '';
        //echo $search;exit;
        $status     = $this->request->status ?? '';
        $stockstatus     = $this->request->stock_status ?? '';
       // \DB::enableQueryLog(); 
        $url =request()->segments();
        $id=$url[2];
        $menus =  Menuitems::query()->select('name','quantity','price','status','stock_status','unit','addons','discount','preparation_time','description','main_category');
        $menus = $menus->where('vendor_id',$id);
        if(!empty($status)){
            $menus = $menus->where('status',$status);
        }
        //  if(\Auth::user()->role != 'admin'){
        //     $menus = $menus->where('vendor_id',\Auth::user()->id);
        // }
         if(!empty($stockstatus)){
            $menus = $menus->where('stock_status',$stockstatus);
        }
        if(!empty($search)){
           // echo $search;exit;
            $menus = $menus->where(function($query) use ($search) {
                    $query->Where('name', 'like', '%'.$search.'%')->orWhere('quantity', 'like', '%'.$search.'%')->orWhere('price', 'like', '%'.$search.'%');
            });
        } 
         //echo "<pre>";print_r(\DB::getQueryLog($menus));exit();
          // dd($menus->get());exit;
          //echo "<pre>";print_r($menus->get()->toArray());exit();
        // echo view('menuitem.menuitemexport', [
        //     'resultData' => $menus->with('categories')->get()
        // ]);exit;
        $cheftype = cheftype($id);
        if($cheftype == "event"){
            return view('menuitem.eventexport', [
                'resultData' => $menus->with('categories')->get(),'slug' => $this->request->slug
            ]);
        } else {  
            return view('menuitem.menuitemexport', [
                'resultData' => $menus->with('categories')->get(),'slug' => $this->request->slug
            ]);
        }
    }
}
