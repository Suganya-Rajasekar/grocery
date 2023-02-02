<?php
   
namespace App\Exports;

   
use App\Models\SubAdmin;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class SubAdminExport implements FromView, WithHeadings
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
            'Status'
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $status     = $this->request->status ?? '';
        $subadmin =  SubAdmin::query()->select('name','email','mobile','status');
        if(!empty($status)){
            $subadmin = $subadmin->where('status',$status);
        }
        if(!empty($search)){
            $subadmin = $subadmin->where(function($query) use ($search) {
                    $query->orWhere('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->orWhere('mobile', 'like', '%'.$search.'%');
            });
        }
        return view('subadmin.subadminexport', [
            'resultData' => $subadmin->get()
        ]);
    }
}
