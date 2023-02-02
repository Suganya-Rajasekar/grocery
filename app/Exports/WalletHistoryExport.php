<?php
namespace App\Exports;

use App\Http\Controllers\Excel;
use App\Models\WalletHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class WalletHistoryExport implements FromView, WithHeadings
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
        /*return [
             'Food Name',
            'Quantity',
            'Revenew',
        ];*/
    }

    public function view(): View
    {
        $user_id = $this->request->user_id;
        $type    = $this->request->type;
         $w_history = WalletHistory::where(function($query) use($user_id,$type){
            if($user_id) {
                $query->where('user_id',$user_id);
            }
            if($type) {
                $query->where('type',$type);
            }
        });
         return view('wallet_history.WalletHistoryExport', [
            'resultData' => $w_history->get()
        ]);

    }
}
