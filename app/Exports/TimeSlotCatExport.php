<?php
   
namespace App\Exports;

   
use App\Models\Timeslotcategory;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class TimeSlotCatExport implements FromView, WithHeadings
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
            'Status',
        ];
    }

    

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $status     = $this->request->status ?? '';
       $tscategories =  Timeslotcategory::query()->select('name','status');
        if(!empty($status)){
            $tscategories = $tscategories->where('status',$status);
        }
        if(!empty($search)){
            // $tscategories = $tscategories->where(function($query) use ($search) {
            //         $query->orWhere('name', 'like', '%'.$search.'%');
            // });
             $tscategories = $tscategories->where('name', 'like', '%'.$search.'%');
        }
        return view('timeslotcategory.timecateexport', [
            'resultData' => $tscategories->get()
        ]);
    }
}
