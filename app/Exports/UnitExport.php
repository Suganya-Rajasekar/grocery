<?php
   
namespace App\Exports;

   
use App\Models\Addon;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class UnitExport implements FromView, WithHeadings
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
        $units =  Addon::query()->select('id','name','type','status')->where('type','unit');
        if(!empty($status)){
            $units = $units->where('status',$status);
        }
        if(!empty($search)){
            $units = $units->where(function($query) use ($search) {
                    $query->orWhere('name', 'like', '%'.$search.'%');
            });
        } 
        return view('unit.unitexport', [
            'resultData' => $units->get()
        ]);
    }
}
