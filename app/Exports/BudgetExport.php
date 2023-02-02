<?php
   
namespace App\Exports;

   
use App\Models\Commondatas;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class BudgetExport implements FromView, WithHeadings
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
        $budgets =  Commondatas::query()->where('type','budget');
        if(!empty($status)){
            $budgets = $budgets->where('status',$status);
        }
        if(!empty($search)){
            $budgets = $budgets->where(function($query) use ($search) {
                    $query->orWhere('name', 'like', '%'.$search.'%');
            });
        }
        return view('common.budgetexport', [
            'resultData' => $budgets->get()
        ]);
    }
}
