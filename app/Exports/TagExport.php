<?php
   
namespace App\Exports;

   
use App\Models\Commondatas;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class TagExport implements FromView, WithHeadings
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
        $tags =  Commondatas::query()->where('type','tag');
        if(!empty($status)){
            $tags = $tags->where('status',$status);
        }
        if(!empty($search)){
            $tags = $tags->where(function($query) use ($search) {
                    $query->orWhere('name', 'like', '%'.$search.'%');
            });
        }
        return view('common.tagexport', [
            'resultData' => $tags->get()
        ]);
    }
}
