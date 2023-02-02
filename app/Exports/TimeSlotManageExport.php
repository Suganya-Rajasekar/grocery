<?php
   
namespace App\Exports;

   
use App\Models\Timeslotmanagement;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class TimeSlotManageExport implements FromView, WithHeadings
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
            'Status',
        ];
    }
    public function view(): View
    {
        $status     = $this->request->status ?? '';
        $tsmanages =  Timeslotmanagement::query()->select('status','start','end');
        if(!empty($status)){
            $tsmanages = $tsmanages->where('status',$status);
        }
        
        return view('timeslotmanagement.timeslotmanexport', [
            'resultData' => $tsmanages->get()
        ]);
    }
}
