<?php
   
namespace App\Exports;

   
use App\Models\Offer;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class OfferExport implements FromView, WithHeadings
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
            'Code',
            'Promo Offer',
            'Validity_Start_Date',
            'Validity_End_Date',
            'Type',
            'Status',
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $promo_type    = $this->request->promo_type ?? '';
        $date       = $this->request->date ?? '';
        $status     = $this->request->status ?? '';
        $offers =  Offer::query()->select('name','promo_code','offer','start_date','end_date','promo_type','status');
        if(!empty($search)){
          $offers = $offers->Where('name', 'like', '%'.$search.'%')->orWhere('promo_code', 'like', '%'.$search.'%')->orWhere('offer', 'like', '%'.$search.'%');
        }
        if(!empty($promo_type)){
            $offers = $offers->Where('promo_type', $promo_type);
        }
        if(!empty($status)){
            $offers = $offers->Where('status', $status);
        }
        if (!empty($date)) {
            $sDate  = explode(" - ",$date);
            $offers = $offers->where('start_date', '>=', $sDate[0])->where('end_date', '<=', $sDate[1]);
        } 
        return view('offer.offerexport', [
            'resultData' => $offers->get()
        ]);
    }
}
