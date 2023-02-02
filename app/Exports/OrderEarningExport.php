<?php
   
namespace App\Exports;

   
use App\Models\Orderdetail;
use App\Models\Customer;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class OrderEarningExport implements FromView, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

   public function __construct($request,$type)
    {
        $this->request = $request;
        $this->type = $type;
    }
    public function headings(): array
    {
        return [
             'Order Id',
            'Chef Name',
            'Customer Name',
            'Commission %',
            'Chef Earnings',
            'Tax %',
            'Delivery charges',
            'Your revenew',
            'Offer %',
            'Offer Amount',
            'Total Amount',
            'Order Date',
        ];
    }

    public function view(): View
    {
        $filter     = $this->request->filter ?? '';
        $vendor_id    = $this->request->vendor_id ?? '';
        $location_id    = $this->request->location_id ?? '';
        $date       = $this->request->date ?? '';

        $Tax        = $this->request->tax ? explode('|',$this->request->tax) : '';
        $Del_charge = $this->request->del_charge ? explode('|',$this->request->del_charge) : '';
        $Revenue    = $this->request->revenue ? explode('|',$this->request->revenue) : '';
        $Offer      = $this->request->offer ? explode('|',$this->request->offer) : '';
        $Commission = $this->request->commission ? explode('|',$this->request->commission) : '';
        $offercode  = $this->request->offercode ? explode('|',$this->request->offercode) : '';
        $customerpaid = $this->request->customer_paid ? explode('|',$this->request->customer_paid) : '';
        $commission_amt = $this->request->commission_amt ? explode('|',$this->request->commission_amt) : '';
        $delivery_place = $this->request->delivery_place ?? ''; 
        $gst_number  = $this->request->gst_number ?? '';
        $earnings =  Orderdetail::query()->select('s_id','total_food_amount','order_id','vendor_id','user_id','commission','vendor_price','tax','tax_amount','package_charge','del_charge','commission_amount','offer_percentage','offer_amount','offer_value','grand_total','created_at')->where('order_type','menuitem');
        if(!empty($filter)){
          $earnings = $earnings->Where('s_id', 'like', '%'.$filter.'%')->orWhere('commission_amount', 'like', '%'.$filter.'%')->orWhere('vendor_price', 'like', '%'.$filter.'%')->orWhere('grand_total', 'like', '%'.$filter.'%')->orWhereHas('getUserDetails', function($query) use($filter) {
                                if (!empty($filter)) {
                                    $query->where('name', 'like', '%'.$filter.'%');
                                }
                            })
                            ->orWhereHas('getVendorDetails', function($query) use($filter) {
                                if (!empty($filter)) {
                                    $query->where('name', 'like', '%'.$filter.'%');
                                }

                            });
        }
        if(!empty($vendor_id)){
            $earnings = $earnings->Where('vendor_id', $vendor_id);
        }
        if (!empty($date)) {
            $sDate  = explode(" - ",$date);
            $earnings    = $earnings->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
        }
        $taxcount   = $Tax ? count($Tax) : 0;
        if($taxcount >= 2) {
            $earnings = $earnings->where('tax_amount',$Tax[0],$Tax[1]);
        }
        $del_charge_count = $Del_charge ? count($Del_charge) : 0;
        if($del_charge_count >= 2) {
            $earnings = $earnings->where('del_charge',$Del_charge[0],$Del_charge[1]);
        }
        $revenue_count = $Revenue ? count($Revenue) : 0;
        if($revenue_count >= 2) {
            $earnings = $earnings->where('commission_amount',$Revenue[0],$Revenue[1]);
        }
        $offer_count = $Offer ? count($Offer) : 0;
        if($offer_count >= 2) {
            $earnings = $earnings->where('offer_percentage',$Offer[0],$Offer[1]);
        }
        $commission_count = $Commission ? count($Commission) : 0;
        if($commission_count >= 2) {
            $earnings = $earnings->where('commission',$Commission[0],$Commission[1]);
        }
        $customerpaid_count  = $customerpaid ? count($customerpaid) : 0;
        if($customerpaid_count >= 2) {
            $earnings = $earnings->where('grand_total',$customerpaid[0],$customerpaid[1]);
        } 
        $offercode_count = $offercode ? count($offercode) : 0;
        if($offercode_count >= 2) {                    
            $earnings = $earnings->whereHas('order',function($qqy) use($offercode){
                $qqy->whereHas('promo',function($qpy) use($offercode){
                    $qpy->where('promo_code',$offercode[0],$offercode[1]);
                });
            }); 
        }
        if(!empty($gst_number)) {
            $earnings = $earnings->whereHas('getVendorDetails',function($gstqry) use($gst_number){
                $gstqry->whereHas('getdocument',function($dqry) use($gst_number){
                    $dqry->where('gst_no',$gst_number);
                });
            });
        }
        $Commission_amt_count = $commission_amt ? count($commission_amt) : 0;
        if($Commission_amt_count >= 2) {
            $earnings = $earnings->where('commission_amount',$commission_amt[0],$commission_amt[1]);
        } 
        if(!empty($delivery_place)) {
            $earnings = $earnings->whereHas('order',function($oqry) use($delivery_place){
              $oqry->whereHas('getUserAddress',function($addqry) use($delivery_place) {
                        $addqry->where('city',$delivery_place);
                    });  
            });
        }
        // header('Content-Type: text/html; charset=utf-8');
        // echo (string)view('earning.orderearexport', [
        //     'resultData' => $earnings->get()
        // ]);
        // exit;
        $earnings = $earnings->with('order')->orderByDesc('id');
        return view('earning.orderearexport', [
            'resultData' => $earnings->get(),'type' =>$this->type
        ]);

    }
}
