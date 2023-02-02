<?php
   
namespace App\Exports;

   
use App\Models\Payout;
use App\Http\Controllers\Excel;
use Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class PayoutExport implements FromView, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

   public function __construct($request)
    {
        $this->payout = $request;

    }
    public function headings(): array
    {
        return [
            'S.No',
            'U-Id',
            'Order Id',
            'Date',
            'Channel',
            'Amount',
        ];
    }

    public function view(): View
    {
        $request = $this->payout ;   
        return view('payout.payoutexport', [
            'request' => $request
        ]);
    }
}
