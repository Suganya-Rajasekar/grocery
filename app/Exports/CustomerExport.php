<?php
   
namespace App\Exports;

   
use App\Models\Customer;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class CustomerExport implements FromView, WithHeadings
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
           'User Code',
            'Name',
            'Email',
            'Mobile',
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $device     = $this->request->device ?? '';
        $customers =  Customer::query()->select(['id','user_code', 'name','email','mobile','device','created_at']);  
        if(!empty($search)){
            $customers = $customers->where(function($query) use ($search) {
                    $query->Where('user_code', 'like', '%'.$search.'%')->orWhere('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->orWhere('mobile', 'like', '%'.$search.'%');
            });
        }
        if(isset($device) && $device != '') {
            $customers = $customers->where('device',$device);
        }
        $customers = $customers->with('Useraddress')->orderByDesc('id');
        return view('customer.customerexport', [
            'resultData' => $customers->get()
        ]);
    }
}
