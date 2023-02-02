<?php
   
namespace App\Exports;

   
use App\Models\Favourites;
use App\Models\Customer;
use App\Http\Controllers\Excel;
use App\Models\Chefs;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class FavouritesExport implements FromView, WithHeadings
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
            'Customer',
            'Vendor',
        ];
    }

    public function view(): View
    {
        
        $user_id    = $this->request->user_id ?? '';
        $vendor_id    = $this->request->vendor_id ?? '';
        
        $favorites =  Favourites::query()->with('getUserDetails')->with('getVendorDetails')->with('getMenuDetails')->select('user_id','vendor_id','id','menu_id');
        if(!empty($user_id)){
            // $favorites = Customer::select('name')->where('id', $user_id);
            $favorites = $favorites->Where('user_id', $user_id);
        }
        if(!empty($vendor_id)){
            // $favorites = Chefs::select('name','avatar')->where('id', $vendor_id);
            $favorites = $favorites->Where('vendor_id', $vendor_id);
        }
        // echo "<pre>";
        //  print_r($favorites->get()->toArray());;;exit();
        return view('favourites.favouritesexport', [
            'resultData' => $favorites->get()
        ]);
    }
}
