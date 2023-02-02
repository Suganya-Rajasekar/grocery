<?php
   
namespace App\Exports;

   
use App\Models\Wishlist;
use App\Models\Customer;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class WishlistExport implements FromView, WithHeadings
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
            'Title',
            'Description'
        ];
    }

    public function view(): View
    {
       
        $user_id    = $this->request->user_id ?? '';
        $filter     = $this->request->filter ?? '';
       $wishlists =  Wishlist::query()->with('getUserDetails')->select('user_id','title','description','id');
        if(!empty($user_id)){
           $wishlists = $wishlists->Where('user_id', $user_id);
        }
        if(!empty($filter)){
            $wishlists = $wishlists->where(function($query) use ($filter) {
                   $query->orWhere('title', 'like', '%'.$filter.'%')->orWhere('description', 'like', '%'.$filter.'%');
        });
    }
         return view('wishlist.wishlistexport', [
            'resultData' => $wishlists->get()
        ]);
    
    }
}
