<?php
   
namespace App\Exports;

   
use App\Models\Page;
use App\Http\Controllers\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
class PageExport implements FromView, WithHeadings
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
            'Title',
            'Slug',
            'Status'
        ];
    }

    public function view(): View
    {
        $search     = $this->request->search ?? '';
        $status     = $this->request->status ?? '';
        $pages =  Page::query()->select('title','slug','status');
        if(!empty($status)){
            $pages = $pages->where('status',$status);
        }
        if(!empty($search)){
            $pages = $pages->where(function($query) use ($search) {
                    $query->orWhere('title', 'like', '%'.$search.'%')->orWhere('slug', 'like', '%'.$search.'%');
            });
        }
        return view('pages.pagesexport', [
            'resultData' => $pages->get()
        ]);
    }
}
