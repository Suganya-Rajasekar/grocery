<?php
   
namespace App\Exports;

use App\Models\Order;
use App\Models\Orderdetail;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class VendorOrderExport implements FromView, WithHeadings
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
           'Order Id',
           'Customer Name',
           'Commission Amount',
           'Total Amount',
           'Status',
        ];
    }

    public function view(): View
    {
        $urltype     = $this->request->urltype ?? '';
        $search     = $this->request->search ?? '';
        $user_id    = $this->request->user_id ?? '';
        $commission_amount    = $this->request->commission_amount ?? '';
        $totamt       = $this->request->totamt ?? ''; 
        $status = $this->request->status ?? '';
        //\DB::enableQueryLog();      
       // $orders = Order::query()->select('id','vendor_price','commission_amount','user_id');
        //$orders = $orders->wherehas('Orderdetail',function ($result) use ($search,$commission_amount,$user_id,$totamt,$status) {
         $orders = Orderdetail::where(function($result) use($search,$user_id,$totamt,$status,$commission_amount,$urltype){
            if(!empty($search)){
                $result->Where('s_id','like', '%'.$search.'%');
            }
            if(\Auth::user()->role != 'admin'){
                $result->where('vendor_id',\Auth::user()->id);
            }
            if(!empty($status)){
                if ($status == 'completed') {
                    $result->where(function($qy){
                        $qy->where('status','=','food_ready')->orWhere('status', '=', 'pickup_boy')->orWhere('status', '=', 'reached_location')->orWhere('status', '=', 'reached_restaurant')->orWhere('status', '=', 'riding')->orWhere('status', '=', 'accepted_boy')->orWhere('status', '=', 'completed');
                    });
                }else{
                    $result->where('status','like', '%'.$status.'%');
                }
            }
            if(!empty($commission_amount)){
                $result->Where('commission_amount', 'like', '%'.$commission_amount.'%');
            }
            if(!empty($totamt)){
              $result->Where('vendor_price', 'like', '%'.$totamt.'%');
            }
            if(!empty($user_id)){
            $result->Where('user_id', $user_id);
            }
            if($urltype != 'all'&& $urltype !='today' && $urltype != 'competed') {
                $result->where('status', 'like', '%'.$urltype.'%');
            } elseif($urltype == 'today') {
                $today  = date('Y-m-d');
                $result->where('date',$today);
            }elseif($urltype == 'competed'){
             $result->where(function($qy){
                $qy->where('status','=','food_ready')->orWhere('status', '=', 'pickup_boy')->orWhere('status', '=', 'reached_location')->orWhere('status', '=', 'reached_restaurant')->orWhere('status', '=', 'riding')->orWhere('status', '=', 'accepted_boy')->orWhere('status', '=', 'completed');
            });
            }
            
        });
       // dd($orders->get());exit;
        //echo "<pre>";print_r(\DB::getQueryLog($orders));exit();
        //echo "<pre>";print_r($orders->get()->toArray());exit();
        return view('order.vendororderexport', [
            'resultData' => $orders->get()
        ]);

    }
}
