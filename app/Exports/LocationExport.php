<?php
   
namespace App\Exports;

   
use App\Models\Location;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class LocationExport implements FromView, WithHeadings
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
             'Location Name',
            'Code',
            'Status'
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $status     = $this->request->status ?? '';
       $pages =  Location::query()->select('name','code','status');
        if(!empty($status)){
            $pages = $pages->where('status',$status);
        }
        if(!empty($search)){
            $pages = $pages->where(function($query) use ($search) {
                    $query->orWhere('name', 'like', '%'.$search.'%')->orWhere('code', 'like', '%'.$search.'%');
            });
        }
        return view('location.locationexport', [
            'resultData' => $pages->get()
        ]);
    }
}
