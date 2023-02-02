<?php
   
namespace App\Exports;

   
use App\Models\Order;
use App\Models\Customer;
use App\Http\Controllers\Excel;
use App\Models\Orderdetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class MisEarningExport implements FromView, WithHeadings
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
            'Order Value',
            'Commission %',
            'Chef Earnings',
            'Tax %',
            'Delivery charges',
            'Your revenew',
            'Offer %',
            'Offer Amount',
            'Total Amount',
            'Order Date',
            'Customer Name',
            'Customer mail',
            'Customer mobile',
            'Chef',
            'Delivery address',
            'Payment Type',
        ];
    }

    public function view(): View
    {
        $filter     = $this->request->filter ?? '';
        $user_id    = $this->request->user_id ?? '';
        
        $date       = $this->request->date ?? '';
        $chefid     = $this->request->chefid ?? '';
        $Tax        = $this->request->tax ? explode('|',$this->request->tax) : '';
        $Del_charge = $this->request->del_charge ? explode('|',$this->request->del_charge) : '';
        $Revenue    = $this->request->revenue ? explode('|',$this->request->revenue) : '';
        $Offer      = $this->request->offer ? explode('|',$this->request->offer) : '';
        $Commission = $this->request->commission ? explode('|',$this->request->commission) : '';
        
        $mis = Orderdetail::query()->with('Order');
        if(!empty($filter)){
          $mis = $mis->Where('s_id', 'like', '%'.$filter.'%')->orWhere('commission_amount', 'like', '%'.$filter.'%')->orWhere('vendor_price', 'like', '%'.$filter.'%')->orWhere('grand_total', 'like', '%'.$filter.'%')->orWhere('total_food_amount', 'like', '%'.$filter.'%')->orWhere('tax', 'like', '%'.$filter.'%')->orWhere('del_charge', 'like', '%'.$filter.'%')->orWhere('offer_percentage', 'like', '%'.$filter.'%')->orWhere('offer_amount', 'like', '%'.$filter.'%')->orWhere('grand_total', 'like', '%'.$filter.'%');
        }
        if (!empty($date)) {
            $sDate  = explode(" - ",$date);
            $mis    = $mis->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
        } 

        if(!empty($chefid)) {
            $mis->where('vendor_id',$chefid);
        }
        $taxcount   = $Tax ? count($Tax) : 0;
        if($taxcount >= 2) {
            $mis = $mis->where('tax',$Tax[0],$Tax[1]);
        }
        $del_charge_count = $Del_charge ? count($Del_charge) : 0;
        if($del_charge_count >= 2) {
            $mis = $mis->where('del_charge',$Del_charge[0],$Del_charge[1]);
        }
        $revenue_count = $Revenue ? count($Revenue) : 0;
        if($revenue_count >= 2) {
            $mis = $mis->where('commission_amount',$Revenue[0],$Revenue[1]);
        }
        $offer_count = $Offer ? count($Offer) : 0;
        if($offer_count >= 2) {
            $mis = $mis->where('offer_percentage',$Offer[0],$Offer[1]);
        }
        $commission_count = $Commission ? count($Commission) : 0;
        if($commission_count >= 2) {
            $mis = $mis->where('commission',$Commission[0],$Commission[1]);
        }
        // echo "<pre>";print_r($mis->get());exit;
        return view('earning.misearexport', [
            'resultData' => $mis->get()
        ]);
    }
}
