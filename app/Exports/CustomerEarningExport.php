<?php
   
namespace App\Exports;

   
use App\Models\Customer;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class CustomerEarningExport implements FromView, WithHeadings
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
            'Name',
            'Email-Id',
            'Mobile No',
            'Date of onboarding',
            'Total orders',
            'Amount spend',
            'City',
        ];
    }


    public function view(): View
    {
        
        $date       = $this->request->date ?? '';
        $onboard_date     = $this->request->onboard_date ?? '';
        $customer_state = $this->request->customer_state ?? ''; 
        $city = $this->request->city ?? '';
        $timeslot       = $this->request->timeslot ? $this->request->timeslot : '';  
        $customers =  Customer::query()->select('name','email','mobile','created_at','id','id')->withCount(['Orders AS countorder' => function($query) use($date){
            $query->completed();
            if(!empty($date)) {
                $sDate  = explode(" - ",$date);
                $query->whereBetween(\DB::raw('date'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
            }
        },'Orders AS spend_amt' => function($qry) use($date){
            $qry->select(\DB::raw("SUM(grand_total) as paidsum"))->completed();
            if(!empty($date)) {
                $sDate  = explode(" - ",$date);
                $qry->whereBetween(\DB::raw('date'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
            }
        },'Orders AS fooditemcount' => function($fquery){
            $fquery->completed()->where('order_type','menuitem'); 
        },'Orders AS fooditem_spend_amt' => function($fsquery){  
            $fsquery->completed()->where('order_type','menuitem')->select(\DB::raw("SUM(grand_total)"));
        },'Orders AS eventcount' => function($equery) {
            $equery->completed()->where('order_type','ticket'); 
        },'Orders AS event_spend_amt' => function($esquery) {
            $esquery->completed()->where('order_type','ticket')->select(\DB::raw("SUM(grand_total)")); 
        }]);
        
        if(!empty($onboard_date)) {
            $oDate  = explode(" - ",$onboard_date);
           $customers = $customers->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($oDate[0])),date('Y-m-d',strtotime($oDate[1]))]);
        }
        if($customer_state){
            ($customer_state == 'ordered') ? $customers->having('countorder','>',0)->orderBy('countorder','desc') : $resultData->having('countorder',0);
        }
        if($city){
            $customers->whereHas('useraddress',function($query) use($city){
                $query->where('address_type','home')->where('city',$city);
            });
        }
        if($timeslot){
            $customers->whereHas('Orders',function($tquery) use($timeslot){
                $tquery->completed()->where('time_slot',$timeslot);
            });
        }
        $customers = $customers->with('useraddress',function($aquery) use($city){
            if($city){
                $aquery->where('city',$city);
            }
        });        
        // echo "<pre>";print_r($customers->get());exit;
        return view('earning.customerearexport', [
            'resultData' => $customers->get()
        ]);
    }
}
