<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use PDF;
use App\Models\Payout;
use App\Models\Invoice;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $pageCount  = 10;
        $page       = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
       $invoices               =   Invoice::paginate(10);
       return view('invoice.index',compact('invoices','page'));
    }
    public function generateInvoice($PayoutId)
    {
        $payout = Payout::with('getVendorDetails')->find($PayoutId);
        if(!empty($payout)){
            if($payout->status == 'processed'){
                $Invoice               =   Invoice::findOrCreate($payout->id);
                $Invoice->payout       =   $payout->id;
                $Invoice->chef         =   $payout->v_id;
                $Invoice->number       =   Str::uuid()->toString();
                $Invoice->start_date   =   $payout->from_date;
                $Invoice->end_date     =   $payout->to_date;
                $Invoice->tcs          =   "";
                $Invoice->save();
                $Revenue               =   json_decode(getPayoutReport($payout->v_id,$payout->from_date,$payout->to_date));
                $Commission            =   $Revenue->deductions->amount;
                // return view('invoice.invoice_download',compact('payout','Commission','Invoice'));
                $pdf = PDF::loadView('invoice.invoice_download',compact('payout','Commission','Invoice'));
                $mainPathString        =   'uploads/user_document/'.$payout->v_id.'/';
                $mainPath              =   base_path($mainPathString);
                if (!\File::exists($mainPath)) {
                    $dc = \File::makeDirectory($mainPath, 0777, true, true);
                }
                $newfilename           =   $Invoice->number.'.pdf';
                $pdf->save($mainPath.$newfilename);
                $Invoice->invoice      =   $newfilename;
                // echo $newfilename;
                $Invoice->save();
            }
        }
    }
}