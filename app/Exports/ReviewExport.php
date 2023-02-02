<?php
   
namespace App\Exports;

   
use App\Models\Review;
use App\Models\Customer;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class ReviewExport implements FromView, WithHeadings
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
            'Order Id',
            'Customer',
            'Vendor',
            'Reviews',
            'Rating',
            'Status',
            'Date'
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $user_id    = $this->request->user_id ?? '';
        $vendor_id    = $this->request->vendor_id ?? '';
        $date       = $this->request->date ?? '';
        $status     = $this->request->status ?? '';
        $rating = $this->request->rating ?? '';
        $reviews =  Review::query()->select('order_id','user_id','vendor_id','reviews','rating','created_at','status');
        if(!empty($search)){
           $reviews = $reviews->Where('reviews', 'like', '%'.$search.'%')->orWhereHas('order', function($query) use($search) {
            $query->where('s_id', 'like', '%'.$search.'%');
             });
        }
        if(!empty($user_id)){
            $reviews = $reviews->Where('user_id', $user_id);
        }
       
        if(!empty($vendor_id)){
            $reviews = $reviews->Where('vendor_id', $vendor_id);
        }
        if(!empty($rating)){
            $reviews = $reviews->Where('rating', $rating);
        }
       if(!empty($status)){
            $reviews = $reviews->Where('status', $status);
        }
        if (!empty($date)) {
            $sDate  = explode(" - ",$date);
            $reviews    = $reviews->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);  
        } 
        
        return view('review.reviewexport', [
            'resultData' => $reviews->get()
        ]);
    }
}
