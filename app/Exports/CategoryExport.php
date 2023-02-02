<?php
   
namespace App\Exports;

   
use App\Models\Category;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class CategoryExport implements FromView, WithHeadings
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
        $categories =  Category::query();
        if(!empty($status)){
            $categories = $categories->where('status',$status);
        }
        if(!empty($search)){
            $categories = $categories->where(function($query) use ($search) {
                    $query->orWhere('name', 'like', '%'.$search.'%');
            });
        }
        return view('category.categoryexport', [
            'resultData' => $categories->get()
        ]);
    }
}
