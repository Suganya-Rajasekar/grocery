<?php
   
namespace App\Exports;

   
use App\Models\Bookmarks;
use App\Models\Customer;
use App\Models\Chefs;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class BookmarkExport implements FromView, WithHeadings
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
        $vendor_id     = $this->request->vendor_id ?? '';

        //$bookmarks =  Bookmarks::query()->select('user_id','vendor_id');
        $bookmarks =  Bookmarks::query()->with('getUserDetails')->with('getVendorDetails')->select('user_id','vendor_id');
        if(!empty($user_id)){
           // $bookmarks = Customer::select('name as customername')->where('id', $user_id);
            $bookmarks = $bookmarks->Where('user_id', $user_id);
            //print_r($user_id);exit;
        }
        if(!empty($vendor_id)){
           // $bookmarks = Chefs::select('name','avatar')->where('id', $vendor_id);
            $bookmarks = $bookmarks->Where('vendor_id', $vendor_id);
            //print_r($vendor_id);exit;
        }
        //echo "<pre>";print_r($bookmarks->get());exit;
        return view('bookmark.bookmarkexport', [
            'resultData' => $bookmarks->get()
        ]);
    }
}
