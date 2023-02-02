<?php
   
namespace App\Exports;

   

use App\Http\Controllers\Excel;
use App\Models\Menuitems;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class ItemEarningExport implements FromView, WithHeadings
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
             'Food Name',
            'Quantity',
            'Revenew',
        ];
    }

    public function view(): View
    {
       
        $filter     = $this->request->filter ?? '';
        $chef       = $this->request->chef ?? '';
       $items =  Menuitems::query()->select('name','quantity','id','restaurant_id','vendor_id')->where('food_type','menuitem');
        if(!empty($filter)){
          $items = $items->where('name', 'like', '%'.$filter.'%');
        }
        if(!empty($chef)) {
            $items = $items->where('vendor_id',$chef); 
        }
        return view('earning.itemexport', [
            'resultData' => $items->get()
        ]);
    }
}
