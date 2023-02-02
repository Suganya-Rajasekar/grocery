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
class OrderCsvExport implements FromView, WithHeadings
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
            'Sno',
            'Order Id',
            'Order Date',
            'Chef Name',
            'Chef ID',
            'Modeof Payment',
            'Order status',
            'Gross revenue',
            'Commission Amount',
            'Commission %',
            'Commission value',
            'Tax for knosh',
            'TCS',
            'TDS',
            'Net receivable',
            'Transfer status',
            'Transfer Date',
            'Transfer ID'
        ];
    }

    public function view(): View
    {
        // echo "<pre>";print_r($this->request->all());exit();
        $urltype                = $this->request->urltype ?? '';
        $search                 = $this->request->search ?? '';
        $user_id                = $this->request->user_id ?? '';
        $vendor_id              = $this->request->vendor_id ?? '';
        $commission_amount1     = $this->request->commission_amount1 ?? '';
        $commission_amount2     = $this->request->commission_amount2 ?? '';
        $customer_order_count1  = $this->request->customer_order_count1 ?? '';
        $customer_order_count2  = $this->request->customer_order_count2 ?? '';
        $status                 = $this->request->status ?? '';
        $status                 = $this->request->status ?? '';
        $payment_type           = $this->request->payment_type ?? '';
        $payment_status         = $this->request->payment_status ?? '';   
        // \DB::enableQueryLog();      
        $orders = Order::query()->select('payment_status','id','payment_type','address_id','vendor_price','commission_amount','grand_total','user_id');
        $orders = $orders->withCount(['Orderdetail'=> function($query)use($search,$status,$urltype){
            if(!empty($search)){
                $query->Where('m_id', 'like', '%'.$search.'%');
            }
            if(!empty($vendor_id)){
                $query->Where('vendor_id',$vendor_id);
            }
            if(!empty($status)){
                if ($status == 'pending') {
                    $query->where('status','=','pending');
                } elseif ($status == 'completed') {
                    $query->where('status','=','completed');
                } elseif ($status == 'food_ready') {
                    $query->where('status','=','food_ready');
                } elseif ($status == 'accepted') {
                    $query->where('status','=','accepted_res')->orWhere('status','=','accepted_admin');
                } elseif ($status == 'on_d_way') {
                    $query->where('status', '=', 'accepted_boy')->orWhere('status', '=', 'pickup_boy')->orWhere('status', '=', 'reached_location');
                }
            }
            if($urltype == 'today'){
                $query->where('date',date('Y-m-d'));
            }
           
        }])->having('Orderdetail_Count','>',0);
         
        if(!empty($customer_order_count1) && !empty($customer_order_count2)){
            $orders     = $orders->withCount('Customerorderdetail as y')->having('y',$customer_order_count1,$customer_order_count2);
        }
        if(!empty($commission_amount1) && !empty($commission_amount2)){
            $orders->Where('commission_amount', $commission_amount1,$commission_amount2);
        }
        if(!empty($user_id)){
            $orders->Where('user_id', $user_id);
        }
        if(!empty($payment_type)){
            $orders->Where('payment_type', $payment_type);
        }
        if(!empty($payment_status)){ 
            $orders->Where('payment_status', $payment_status);
        }
        return view('order.ordercsvexport', [
            'resultData' => $orders->get()
        ]);
    }
}
