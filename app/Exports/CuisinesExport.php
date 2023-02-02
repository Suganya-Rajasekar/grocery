<?php
   
namespace App\Exports;

   
use App\Models\Cuisines;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class CuisinesExport implements FromView, WithHeadings
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
            'Description'.
            'Status',
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $status     = $this->request->status ?? '';
        $cuisine =  Cuisines::query()->select('name','description','status');
        if(!empty($status)){
            $cuisine = $cuisine->where('status',$status);
        }
        if(!empty($search)){
            $cuisine = $cuisine->where(function($cqy) use ($search) {
                    $cqy->orWhere('name', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        return view('cuisines.cuisineexport', [
            'resultData' => $cuisine->get()
        ]);
    }
}
