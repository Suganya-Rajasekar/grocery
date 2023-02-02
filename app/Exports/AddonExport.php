<?php
   
namespace App\Exports;

   
use App\Models\Addon;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class AddonExport implements FromView, WithHeadings
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
            'Price',
            'Status',
        ];
    }
    
    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $status     = $this->request->status ?? '';
        $url =request()->segments();
        $id=$url[2];
        $addons =  Addon::query()->select('id','name','type','status','price')->where('type','addon');
        $addons = $addons->where('user_id',$id);
        //dd($addons->get());exit;
        if(!empty($status)){
            $addons = $addons->where('status',$status);
        }
        // if(\Auth::user()->role != 'admin'){
        //     $addons = $addons->where('user_id',\Auth::user()->id);
        // }
        if(!empty($search)){
            $addons = $addons->where(function($query) use ($search) {
                    $query->Where('name', 'like', '%'.$search.'%')->orWhere('price', 'like', '%'.$search.'%');
            });
        } 
        return view('addon.addonexport', [
            'resultData' => $addons->get()
        ]);
    }
}
