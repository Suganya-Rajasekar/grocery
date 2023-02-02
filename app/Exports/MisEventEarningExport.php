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
class MisEventEarningExport implements FromView, WithHeadings
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
            'Tax %',
            'Offer %',
            'Offer Amount',
            'Total Amount',
            'Order Date',
            'Customer Name',
            'Customer mail',
            'Customer mobile',
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
        $Offer      = $this->request->offer ? explode('|',$this->request->offer) : '';
        
        $mis = Orderdetail::query()->where('order_type','ticket')->with('Order');
        if(!empty($filter)){
          $mis = $mis->Where('s_id', 'like', '%'.$filter.'%')->orWhere('total_food_amount', 'like', '%'.$filter.'%')->orWhere('tax', 'like', '%'.$filter.'%')->orWhere('offer_percentage', 'like', '%'.$filter.'%')->orWhere('offer_amount', 'like', '%'.$filter.'%')->orWhere('grand_total', 'like', '%'.$filter.'%');
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
        $offer_count = $Offer ? count($Offer) : 0;
        if($offer_count >= 2) {
            $mis = $mis->where('offer_percentage',$Offer[0],$Offer[1]);
        }

        return view('earning.miseventexport', [
            'resultData' => $mis->orderBy('id','desc')->get()
        ]);
    }
}
