<?php
   
namespace App\Exports;

   
use App\Models\Chefs;
use App\Http\Controllers\Excel;
use App\Models\Location;
use Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class ChefExport implements FromView, WithHeadings
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
            'Location Name',
            'Status',
            'Registered Date',
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $location_id    = $this->request->location_id ?? '';
        $date       = $this->request->date ?? '';
        $status     = $this->request->status ?? '';
        // \DB::enableQueryLog();
        $chefs      =  Chefs::query()->select('name','email','mobile','location_id','status','created_at','id');
        $chefs = $chefs->wherehas('locations',function ($result) use ($search,$date,$location_id,$status){
        if(!empty($search)){
         $result->Where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->orWhere('mobile', 'like', '%'.$search.'%');
        }
        if(!empty($location_id)){
            $result->Where('locations.id', $location_id);
        }
        if(!empty($status)){
           $result->Where('users.status', $status);
        }
        if (!empty($date)) {
            $sDate  = explode(" - ",$date);
            $result->whereBetween(\DB::raw('substr(users.created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
        } 
    });
         // $chefs = $chefs->get();
         //    echo "<pre>";print_r(\DB::getQueryLog());exit();
        // echo "<pre>";print_r($orders->get()->toArray());exit();
        return view('chef.chefexport', [
            'resultData' => $chefs->get() 
        ]);
    }
}
