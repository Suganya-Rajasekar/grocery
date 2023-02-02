<?php
   
namespace App\Exports;

   
use App\Models\Explore;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class ExploreExport implements FromView, WithHeadings
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
            'Tags',
            'Status',
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $status     = $this->request->status ?? '';
        $explores =  Explore::query()->select(['name', 'slug','status']);
        if(!empty($status)){
            $explores = $explores->where('status',$status);
        }
        if(!empty($search)){
            $explores = $explores->where(function($query) use ($search) {
                    $query->orWhere('name', 'like', '%'.$search.'%');
            });
        }
        return view('explore.exploreexport', [
            'resultData' => $explores->get()
        ]);
    }
}
