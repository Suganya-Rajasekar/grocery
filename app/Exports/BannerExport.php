<?php
   
namespace App\Exports;

   
use App\Models\Banner;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class BannerExport implements FromView, WithHeadings
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
            'Start Date',
            'Status',
        ];
    }

    public function view(): View
    {
        $date       = $this->request->date ?? '';
        $status     = $this->request->status ?? '';
        $banners =  Banner::query()->select('start_date','end_date','status');
        
        if(!empty($status)){
            $banners = $banners->Where('status', $status);
        }
        if (!empty($date)) {
            
            $sDate  = explode(" - ",$date);
            $banners = $banners->where('start_date', '>=', $sDate[0])->where('end_date', '<=', $sDate[1]);
        } 
        return view('banner.bannerexport', [
            'resultData' => $banners->get()
        ]);
    }
}
