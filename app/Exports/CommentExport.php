<?php
   
namespace App\Exports;

   
use App\Models\Comment;
use App\Models\Customer;
use App\Http\Controllers\Excel;
use App\Models\Menuitems;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class CommentExport implements FromView, WithHeadings
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
            'Customer Name',
            'Food Name',
            'Comment',
            'Status',
            'DateTime'
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $user_id    = $this->request->user_id ?? '';
        $food_id    = $this->request->food_id ?? '';
        $date       = $this->request->date ?? '';
        $status     = $this->request->status ?? '';
        $comment    =  Comment::query()->select('user_id','food_id','comment','status','created_at');
        if(!empty($search)){
          $comment = $comment->Where('comment', 'like', '%'.$search.'%');
        }
        if(!empty($user_id)){
            $comment = $comment->Where('user_id', $user_id);
        }
        if(!empty($food_id)){
            $comment = $comment->Where('food_id', $food_id);
        }
        if(!empty($status)){
            $comment = $comment->Where('status', $status);
        }
        if (!empty($date)) {
            
            $sDate  = explode(" - ",$date);
            $comment    = $comment->whereBetween(\DB::raw('substr(created_at, 1, 10)'),[date('Y-m-d',strtotime($sDate[0])),date('Y-m-d',strtotime($sDate[1]))]);
        } 
        return view('comment.commentexport', [
            'resultData' => $comment->get()
        ]);
    }
}
